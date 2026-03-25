<?php

namespace App\Http\Controllers;

use App\Models\Cde;
use App\Models\CdeLigne;
use App\Models\Commentaire;
use App\Models\Ddp;
use App\Models\DdpCdeStatut;
use App\Models\DdpLigneFournisseur;
use App\Models\Entite;
use App\Models\Famille;
use App\Models\Mailtemplate;
use App\Models\Matiere;
use App\Models\ModelChange;
use App\Models\SocieteContact;
use App\Models\SocieteMatiere;
use App\Models\SocieteMatierePrix;
use App\Models\Unite;
use App\Models\User;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mail;
use Response;
use Storage;

class DdpController extends Controller
{

    private function getExcelColumnName($index)
    {
        $letters = '';
        while ($index >= 0) {
            $letters = chr($index % 26 + 65) . $letters;
            $index = intval($index / 26) - 1;
        }
        return $letters;
    }
    public function indexDdp_cde()
    {

        return view('ddp_cde.index');
    }
    public function index(Request $request)
    {
        // Validation des entrées
        $request->validate([
            'search' => 'nullable|string|max:255',
            'statut' => 'nullable|integer|exists:ddp_cde_statuts,id',
            'nombre' => 'nullable|integer|min:1|',
            'sort' => 'nullable|string|in:code,created_at,nom,user,statut',
            'direction' => 'nullable|string|in:asc,desc',
        ]);

        // Lecture des entrées avec des valeurs par défaut
        $search = $request->input('search');
        $statut = $request->input('statut');
        $quantite = $request->input('nombre', 100);
        $sort = $request->input('sort', 'code');
        $direction = $request->input('direction', 'desc');

        // Construire la requête de base
        $query = Ddp::query()
            ->where('ddps.nom', '!=', 'undefined')
            ->with(['entite', 'user', 'ddpCdeStatut']) // Charger les relations nécessaires
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('ddps.nom', 'ILIKE', "%{$search}%")
                        ->orWhere('code', 'ILIKE', "%{$search}%")
                        ->orWhereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('first_name', 'ILIKE', "%{$search}%")
                                ->orWhere('last_name', 'ILIKE', "%{$search}%");
                        });
                });
            })
            ->when($statut, function ($query, $statut) {
                $query->where('ddp_cde_statut_id', $statut);
            });

        // Appliquer le tri avec groupement par entité
        switch ($sort) {
            case 'code':
                $query->orderBy('ddps.entite_id', 'asc')
                    ->orderBy('ddps.code', $direction);
                break;
            case 'nom':
                $query->orderBy('ddps.entite_id', 'asc')
                    ->orderBy('ddps.nom', $direction);
                break;
            case 'user':
                $query->join('users', 'ddps.user_id', '=', 'users.id')
                    ->orderBy('ddps.entite_id', 'asc')
                    ->orderBy('users.first_name', $direction)
                    ->orderBy('users.last_name', $direction)
                    ->select('ddps.*');
                break;
            case 'statut':
                $query->join('ddp_cde_statuts', 'ddps.ddp_cde_statut_id', '=', 'ddp_cde_statuts.id')
                    ->orderBy('ddps.entite_id', 'asc')
                    ->orderBy('ddp_cde_statuts.nom', $direction)
                    ->select('ddps.*');
                break;
            case 'created_at':
                $query->orderBy('ddps.entite_id', 'asc')
                    ->orderBy('ddps.created_at', $direction);
                break;
            default:
                // Tri par défaut : entité puis statut puis date de création
                $query->orderBy('ddps.entite_id', 'asc')
                    ->orderBy('ddps.ddp_cde_statut_id', 'asc')
                    ->orderBy('ddps.created_at', 'desc');
                break;
        }

        // Récupérer les résultats paginés
        $ddps = $query->paginate($quantite);

        // Grouper par entité pour la vue - convertir d'abord en collection puis grouper
        $ddpsGrouped = $ddps->getCollection()->groupBy('entite.name');

        // Récupérer les statuts pour le filtre
        $ddp_statuts = DdpCdeStatut::all();

        // Retourner la vue avec les données
        return view('ddp_cde.ddp.index', compact('ddps', ['ddp_statuts', 'ddpsGrouped', 'sort', 'direction']));
    }

    public function indexColDdpSmall()
    {
        return DdpController::indexColDdp(true);
    }
    public function indexColDdp($isSmall = false)
    {
        $ddps = Ddp::whereIn('ddp_cde_statut_id', [1, 2])->orderBy('ddp_cde_statut_id', 'asc')
            ->where('nom', '!=', 'undefined')
            ->take($isSmall ? 7 : 30)->get();
        $ddps->load('user');
        $ddps->load('ddpCdeStatut');

        return view('ddp_cde.ddp.index_col', compact('ddps', 'isSmall'));
    }
    /*
 ######  ##     ##  #######  ##      ##
##    ## ##     ## ##     ## ##  ##  ##
##       ##     ## ##     ## ##  ##  ##
 ######  ######### ##     ## ##  ##  ##
      ## ##     ## ##     ## ##  ##  ##
##    ## ##     ## ##     ## ##  ##  ##
 ######  ##     ##  #######   ###  ###
*/
    public function show($id)
    {
        $ddp = Ddp::findOrFail($id);
        if ($ddp->ddp_cde_statut_id == 1) {
            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe');
            $ddpid =  $ddp->id;
            $familles = Famille::all();
            $unites = Unite::all();
            $entites = Entite::all();
            $entite_code = Entite::findOrFail($ddp->entite_id)->id;
            $lastcode = Ddp::where('code', 'LIKE', 'DDP-' . date('y') . '%')
                ->where('entite_id', $ddp->entite_id)
                ->orderBy('code', 'desc')
                ->first();
            if ($entite_code == 1) {
                $entite_code = '';
            } elseif ($entite_code == 2) {
                $entite_code = 'AV';
            } elseif ($entite_code == 3) {
                $entite_code = 'AMB';
            }
            $lastNumber = $lastcode ? intval(explode('-', $lastcode->code)[2]) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            if ($ddp->code == 'undefined') {
                $ddp->code = "DDP-" . date('y') . "-" . $newNumber . $entite_code;
                $ddp->save();
            }
            return view('ddp_cde.ddp.create', ['ddp' => $ddp, 'ddpid' => $ddpid, 'familles' => $familles, 'unites' => $unites, 'entites' => $entites, 'entite_code' => $entite_code]);
        }
        if ($ddp->ddp_cde_statut_id == 2) {
            $ddpLignes = $ddp->ddpLigne->where('ligne_autre_id', null);
            $ddp_societes = $ddpLignes->map(function ($ligne) {
                return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                    return $fournisseur->societe;
                });
            })->flatten()->unique('id');
            $table_data = $this->getRetours($id);

            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe', 'ddpLigne.ddpLigneFournisseur.societeContact');
            $data = [];
            // Première ligne : Noms des sociétés
            $row = [];
            foreach ($ddp_societes as $societe) {
                $row[] = $societe->raison_sociale;
                $row[] = $societe->raison_sociale;
                $row[] = $societe->raison_sociale;
                $row[] = $societe->raison_sociale;
            }
            $data[] = $row;

            // Lignes des données
            foreach ($table_data as $index => $row) {
                $row = array_map(function ($value) {
                    return $value;
                }, $row);
                $data[] = $row;
            }

            // Dernière ligne : Formules de calcul
            $row = [];
            $indexSociete = 0;
            while ($indexSociete < (count($ddp_societes) * 4)) {
                $colPrix = $this->getExcelColumnName($indexSociete + 1); // Colonne pour la somme
                $colunite = 'UNDEFINED';
                $colDate = $this->getExcelColumnName($indexSociete + 3); // Colonne pour le minimum
                $rowCount = count($ddpLignes) + 1;
                $row[] = "UNDEFINED";
                $row[] = "=SUM({$colPrix}2:{$colPrix}{$rowCount})";
                $row[] = $colunite;
                $row[] = "=IF(MINIFS({$colDate}2:{$colDate}{$rowCount}, {$colDate}2:{$colDate}{$rowCount}, \">=\" & TODAY())=0, \"\", MINIFS({$colDate}2:{$colDate}{$rowCount}, {$colDate}2:{$colDate}{$rowCount}, \">=\" & TODAY()))";

                $indexSociete += 4;
            }
            $data[] = $row;
            return view('ddp_cde.ddp.retours', compact('ddp', ['ddp_societes', 'data']));
        }
        if ($ddp->ddp_cde_statut_id == 3) {
            $ddp_societes = $ddp->ddpLigne->map(function ($ligne) {
                return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                    return $fournisseur->societe;
                });
            })->flatten()->unique('id');
            $ddp_societe_contacts = $ddp->ddpLigne->map(function ($ligne) {
                return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                    return $fournisseur->societeContact;
                });
            })->flatten()->unique('id');
            $table_data = $this->getRetours($id);

            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe', 'ddpLigne.ddpLigneFournisseur.societeContact');
            $data = [];
            $row = [];
            // Lignes des données
            foreach ($table_data as $index => $row) {
                $row = array_map(function ($value) {
                    return $value;
                }, $row);
                $data[] = $row;
            }

            // Dernière ligne : Formules de calcul
            $row = [];
            $indexSociete = 0;
            while ($indexSociete < (count($ddp_societes) * 3)) {


                $sum = 0;
                foreach ($table_data as $dataRow) {
                    $dataRow[$indexSociete] = preg_replace('/[^\d.,]/', '', $dataRow[$indexSociete]);
                    $sum += (float)$dataRow[$indexSociete];
                }
                $row[] = ($sum != 0) ? formatNumberArgent($sum) : '';
                $sum = 0;
                foreach ($table_data as $dataRow) {
                    $dataRow[$indexSociete + 1] = preg_replace('/[^\d.,]/', '', $dataRow[$indexSociete + 1]);
                    $sum += (float)$dataRow[$indexSociete + 1];
                }
                $row[] = ($sum != 0) ? formatNumberArgent($sum) : '';

                $dates = array_filter(array_column($table_data, $indexSociete + 2));
                $closestDate = null;
                if (!empty($dates)) {
                    $closestDate = min(array_map(function ($date) {
                        return Carbon::hasFormat($date, 'd/m/Y') ? Carbon::createFromFormat('d/m/Y', $date) : null;
                    }, $dates));
                }
                $row[] = $closestDate ? $closestDate->format('d/m/Y') : '';

                $indexSociete++;
                $indexSociete++;
                $indexSociete++;
            }
            $data[] = $row;
            $ddplignes = $ddp->ddpLigne->where('ligne_autre_id', null)->values();
            return view('ddp_cde.ddp.show', compact('ddp', ['ddp_societes', 'data', 'ddplignes', 'ddp_societe_contacts']));
        } elseif ($ddp->ddp_cde_statut_id == 4) {
            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe');
            $ddpid =  $ddp->id;
            $familles = Famille::all();
            $unites = Unite::all();
            $entites = Entite::all();
            $ddp_societes = $ddp->ddpLigne->map(function ($ligne) {
                return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                    return $fournisseur->societe;
                });
            })->flatten()->unique('id');
            $data = [];
            $row = [];
            // Lignes des données
            $table_data = $this->getRetours($id);
            foreach ($table_data as $index => $row) {
                $row = array_map(function ($value) {
                    return $value;
                }, $row);
                $data[] = $row;
            }
            return view('ddp_cde.ddp.show_annule', [
                'ddp' => $ddp,
                'ddpid' => $ddpid,
                'familles' => $familles,
                'unites' => $unites,
                'entites' => $entites,
                'ddp_societes' => $ddp_societes,
                'data' => $data
            ]);
        }
    }
    public function create(Request $request)
    {
        if ($request->has('affaire_id')) {
            $affaire = \App\Models\Affaire::find($request->input('affaire_id'));
            if ($affaire && ($affaire->statut === \App\Models\Affaire::STATUT_TERMINE || $affaire->statut === \App\Models\Affaire::STATUT_ARCHIVE)) {
                return redirect()->back()->with('error', 'Impossible de créer une demande de prix pour une affaire terminée ou archivée.');
            }
        }

        Ddp::where('nom', 'undefined')->delete();
        $commentaire_id = Commentaire::create([
            'contenu' => '',
        ])->id;
        $ddp = Ddp::create([
            'code' => 'undefined',
            'nom' => 'undefined',
            'ddp_cde_statut_id' => 1,
            'entite_id' => 1,
            'user_id' => Auth::id(),
            'commentaire_id' => $commentaire_id,
            'affaire_id' => $request->input('affaire_id'),
        ]);
        $ddpid =  $ddp->id;

        // Si matiere_id et quantite sont fournis, créer une ligne DDP pré-remplie
        if ($request->has('matiere_id') && $request->has('quantite')) {
            $matiere_id = $request->input('matiere_id');
            $quantite = $request->input('quantite');

            $ddp->ddpLigne()->create([
                'matiere_id' => $matiere_id,
                'quantite' => $quantite,
            ]);
        }

        return redirect()->route('ddp.show', $ddpid);
    }
    ######     ###    ##     ## ########
    ##    ##   ## ##   ##     ## ##
    ##        ##   ##  ##     ## ##
    ######  ##     ## ##     ## ######
    ## #########  ##   ##  ##
    ##    ## ##     ##   ## ##   ##
    ######  ##     ##    ###    ########


    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'ddp_id' => 'required|integer|exists:ddps,id',
            'entite_id' => 'required|integer|exists:entites,id',
            'code' => 'required|string|max:4',
            'nom' => 'required|string|max:255',
            'matieres' => 'required|array',
            'matieres.*.id' => 'nullable|integer|exists:matieres,id',
            'matieres.*.quantity' => 'nullable|numeric|min:0',
            // 'matieres.*.unite_id' => 'required|integer|exists:unites,id',
            'matieres.*.fournisseurs' => 'nullable|array',
            'matieres.*.fournisseurs.*' => 'nullable|string|max:255',
            'matieres.*.ligne_autre_id' => 'nullable|string',
            'matieres.*.case_ref' => 'nullable|string|max:255',
            'matieres.*.case_designation' => 'nullable|string|max:255',
            'matieres.*.case_quantite' => 'nullable|string|max:255',
        ]);
        DB::beginTransaction();
        if ($validator->fails()) {
            DB::rollBack();
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $entite_code = Entite::findOrFail($request->entite_id)->id;
        if ($entite_code == 1) {
            $entite_code = '';
        } elseif ($entite_code == 2) {
            $entite_code = 'AV';
        } elseif ($entite_code == 3) {
            $entite_code = 'AMB';
        }
        if ($request->code && ctype_digit($request->code)) {
            $code = str_pad($request->code, 4, '0', STR_PAD_LEFT);
        } else {
            DB::rollBack();
            return response()->json(['error' => 'Invalid code format'], 400);
        }
        try {
            $ddp = Ddp::findOrFail($request->ddp_id);
            if ($ddp->affaire && $ddp->affaire->statut === \App\Models\Affaire::STATUT_TERMINE) {
                DB::rollBack();
                return response()->json(['error' => 'Impossible de modifier une demande de prix liée à une affaire terminée.'], 403);
            }
            $ddp->entite_id = $request->entite_id;
            $ddp->nom = $request->nom;
            $ddp->code = "DDP-" . date('y') . "-" . $code . $entite_code;
            $ddp->save();
            $ddp->ddpLigne()->delete();
            foreach ($request->matieres as $matiere) {
                if (isset($matiere['ligne_autre_id'])) {
                    $ddpLigne = $ddp->ddpLigne()->updateOrCreate(
                        [
                            'ligne_autre_id' => $matiere['ligne_autre_id'],
                            'ddp_id' => $ddp->id
                        ],
                        [
                            'case_ref' => $matiere['case_ref'],
                            'case_designation' => $matiere['case_designation'],
                            'case_quantite' => $matiere['case_quantite'],

                        ]
                    );
                } else {
                    $ddpLigne = $ddp->ddpLigne()->updateOrCreate(
                        ['matiere_id' => $matiere['id']],
                        [
                            'quantite' => $matiere['quantity'],
                            // 'unite_id' => $matiere['unite_id'],
                            'ddp_id' => $ddp->id
                        ]
                    );
                    foreach ($matiere['fournisseurs'] as $fournisseur) {
                        $ddpLigne->ddpLigneFournisseur()->updateOrCreate(
                            ['societe_id' => $fournisseur],
                            [
                                'ddp_ligne_id' => $ddpLigne->id,
                                'ddp_cde_statut_id' => 1,
                                // 'unite_id' => $matiere['unite_id'],
                            ]
                        );
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => 'Demande de prix sauvegardée avec succès']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'erreur de sauvegarde', 'message' => $e->getMessage()], 500);
        }
    }
    public function destroy($id): RedirectResponse
    {

        $ddp = Ddp::findOrFail($id);
        if ($ddp->affaire && $ddp->affaire->statut === \App\Models\Affaire::STATUT_TERMINE) {
            return back()->with('error', 'Impossible de supprimer une demande de prix liée à une affaire terminée.');
        }
        $ddp->delete();
        return redirect()->route('ddp_cde.index');
    }
    public function validation($id): View|RedirectResponse
    {
        $ddp = Ddp::findOrFail($id)->load('ddpLigne', 'ddpLigne.ddpLigneFournisseur');
        if ($ddp->nom == 'undefined') {
            return redirect()->route('ddp.show', $ddp->id)->with('error', 'Veuillez renseigner la demande de prix');
        }
        if ($ddp->ddpLigne->isEmpty()) {
            return redirect()->route('ddp.show', $ddp->id)->with('error', 'Veuillez renseigner au moins une ligne de la demande de prix');
        }
        if ($ddp->ddpLigne->every(function ($ligne) {
            return $ligne->ligne_autre_id !== null;
        })) {
            return redirect()->route('ddp.show', $ddp->id)->with('error', 'Veuillez ajouter au moins une ligne avec une matière.');
        }
        $ddp_societe = $ddp->ddpLigne->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                return $fournisseur->societe;
            });
        })->flatten()->unique('id');
        $users = User::all();
        $entite = $ddp->entite;
        return view('ddp_cde.ddp.validation', ['ddp' => $ddp, 'societes' => $ddp_societe, 'users' => $users, 'entite' => $entite]);
    }
    public function validate($ddp, Request $request): View
    {

        $ddp = Ddp::findOrFail($ddp)->load('ddpLigne', 'ddpLigne.ddpLigneFournisseur');

        foreach ($request->all() as $key => $value) {
            if (preg_match('/^contact-\d+$/', $key)) {
                $societe_id = explode('-', $key)[1];
                $ddpLigneFournisseurs = DdpLigneFournisseur::whereHas('ddpLigne', function ($query) use ($ddp) {
                    $query->where('ddp_id', $ddp->id);
                })
                    ->where('societe_id', $societe_id)
                    ->get();
                foreach ($ddpLigneFournisseurs as $ddpLigneFournisseur) {
                    $ddpLigneFournisseur->societe_contact_id = $value;
                    $ddpLigneFournisseur->save();
                }
            }
        }
        if ($request->dossier_suivi_par_id != 0) {
            $ddp->dossier_suivi_par_id = $request->dossier_suivi_par_id;
        }
        if (isset($request->date_rendu)) {
            $ddp->date_rendu = $request->date_rendu;
        } else {
            $ddp->date_rendu = null;
        }
        $ddp->afficher_destinataire = $request->afficher_destinataire ? 1 : 0;
        $ddp->save();
        $ddpannee = explode('-', $ddp->code)[1];
        DdpController::pdf($ddp->id);

        $pdfs = Storage::files('DDP/' . $ddpannee);
        $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
            return strpos(basename($file), $ddp->code) === 0;
        });
        $pdfs = array_map(function ($file) use ($ddpannee) {
            return str_replace('DDP/' . $ddpannee . '/', '', $file);
        }, $pdfs);

        if (count($pdfs) != $ddp->societeContacts()->count()) {
            foreach ($pdfs as $pdf) {
                Storage::delete('DDP/' . $ddpannee . '/' . $pdf);
            }
            DdpController::pdf($ddp->id);
            $pdfs = Storage::files('DDP/' . $ddpannee);
            $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
                return strpos(basename($file), $ddp->code) === 0;
            });
            $pdfs = array_map(function ($file) use ($ddpannee) {
                return str_replace('DDP/' . $ddpannee . '/', '', $file);
            }, $pdfs);
        }

        $mailtemplate = Mailtemplate::where('nom', 'ddp')->first();
        $mailtemplate->sujet = str_replace('{code_ddp}', $ddp->code, $mailtemplate->sujet);
        return view('ddp_cde.ddp.pdf_preview', ['ddp' => $ddp, 'pdfs' => $pdfs, 'mailtemplate' => $mailtemplate]);
    }
    public function cancelValidate($id): RedirectResponse
    {
        $ddp = Ddp::findOrFail($id);
        $ddp->ddp_cde_statut_id = 1;
        $ddp->save();
        foreach ($ddp->ddpLigne as $ddpLigne) {
            foreach ($ddpLigne->ddpLigneFournisseur as $ddpLigneFournisseur) {
                DB::table('societe_matiere_prixs')->where('ddp_ligne_fournisseur_id', $ddpLigneFournisseur->id)->delete();
                $ddpLigneFournisseur->date_livraison = null;
                $ddpLigneFournisseur->save();
            }
        }
        return redirect()->route('ddp.show', $ddp->id);
    }
    public function pdf($ddpi_id)
    {
        $ddp = Ddp::findOrFail($ddpi_id)->load('ddpLigne', 'ddpLigne.ddpLigneFournisseur');
        $ddp_contacts = $ddp->ddpLigne->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                return $fournisseur->societeContact;
            });
        })->flatten()->unique('id');
        foreach ($ddp_contacts as $contacts) {
            $lignes = $ddp->ddpLigne->filter(function ($ligne) use ($contacts) {
                return $ligne->ddpLigneFournisseur->contains(function ($fournisseur) use ($contacts) {
                    return $fournisseur->societe_contact_id == $contacts->id;
                });
            });
            $ligne_autres = $ddp->ddpLigne->filter(function ($ligne) use ($contacts) {
                return $ligne->ligne_autre_id != null;
            });
            $etablissement = $contacts->etablissement;
            $afficher_destinataire = $ddp->afficher_destinataire;
            $destinataire = $contacts->email;
            $fileName = $ddp->code . '_' . $etablissement->societe->raison_sociale . '.pdf';
            $entite = $ddp->entite;
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('ddp_cde.ddp.pdf', ['etablissement' => $etablissement, 'ddp' => $ddp, 'lignes' => $lignes, 'afficher_destinataire' => $afficher_destinataire, 'destinataire' => $destinataire, 'entite' => $entite, 'ligne_autres' => $ligne_autres]);
            $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            $pdf->output();
            $domPdf = $pdf->getDomPDF();
            $canvas = $domPdf->get_canvas();
            $canvas->page_text(
                $canvas->get_width() / 2 - 25,
                $canvas->get_height() - 18,
                "Page {PAGE_NUM} sur {PAGE_COUNT}",
                null,
                8,
                [0, 0, 0]
            );

            $year = now()->format('y');
            Storage::put('DDP/' . $year . '/' . $fileName, $pdf->output());
            $pdf = null;
        }
    }
    public function pdfshow($ddp, $dossier, $path)
    {
        $path = 'DDP/' . $dossier . '/' . $path;
        $file = Storage::get($path);
        $type = 'application/pdf';
        $response = Response::make($file, 200);
        $response->header('Content-Type', $type);
        return $response;
    }
    public function pdfDownload($ddp, $dossier, $path)
    {
        $path = 'DDP/' . $dossier . '/' . $path;
        return Storage::download($path);
    }

    public function pdfsDownload($ddp_id)
    {
        $ddp = Ddp::findOrFail($ddp_id);
        $ddpannee = explode('-', $ddp->code)[1];
        $pdfs = Storage::files('DDP/' . $ddpannee);
        $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
            return strpos(basename($file), $ddp->code) === 0;
        });
        $pdfs = array_map(function ($file) use ($ddpannee) {
            return str_replace('DDP/' . $ddpannee . '/', '', $file);
        }, $pdfs);
        if (count($pdfs) == 0) {
            return redirect()->back()->with('error', 'Aucun fichier à télécharger');
        }
        if (count($pdfs) == 1) {
            return response()->download(storage_path('app/private/DDP/' . $ddpannee . '/' . $pdfs[0]));
        }
        $zip = new \ZipArchive();
        $zipFileName = $ddp->code . '.zip';
        $tempDir = storage_path('app/private/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $zip->open($tempDir . '/' . $zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach ($pdfs as $pdf) {
            $filePath = storage_path('app/private/DDP/' . $ddpannee . '/' . $pdf);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $pdf);
            }
        }
        $zip->close();
        return response()->download(storage_path('app/private/temp/' . $zipFileName))->deleteFileAfterSend(true);
    }
    public function sendMails(Request $request, $id)
    {
        $ddp = Ddp::findOrFail($id);
        $request->validate([
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);
        $contenu = str_replace("CHEVRON-GAUCHE", "<", $request->contenu);
        $contenu = str_replace("CHEVRON-DROIT", ">", $contenu);
        $ddpannee = explode('-', $ddp->code)[1];
        $pdfs = Storage::files("DDP/{$ddpannee}");
        $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
            return strpos(basename($file), $ddp->code) === 0;
        });
        $pdfs = array_map(function ($file) use ($ddpannee) {
            return str_replace("DDP/{$ddpannee}/", '', $file);
        }, $pdfs);
        $contacts_Already_sent = [];
        foreach ($ddp->ddpLigne as $ligne) {
            foreach ($ligne->ddpLigneFournisseur as $fournisseur) {
                $societe = $fournisseur->societe;
                $contact = $fournisseur->societeContact;
                $pdfFileName = "{$ddp->code}_{$societe->raison_sociale}.pdf";
                $pdfPath = storage_path("app/private/DDP/{$ddpannee}/{$pdfFileName}");

                if (file_exists($pdfPath) && !in_array($contact->id, $contacts_Already_sent)) {
                    $signaturePath = storage_path('app/private/signature/signature.png'); // Assurez-vous que le chemin est correct

                    try {
                        Mail::send([], [], function ($message) use ($request, $contact, $pdfPath, $signaturePath, &$contenu) {
                            $message->to($contact->email)
                                ->subject($request->sujet)
                                ->attach($pdfPath);

                            if (file_exists($signaturePath) && is_readable($signaturePath)) {
                                $embeddedImage = $message->embed($signaturePath);
                                $contenu .= '<img src="' . $embeddedImage . '" alt="Signature" class="max-w-full h-auto mb-8">';
                            } else {
                                Log::error('Signature image not found or not readable at path: ' . $signaturePath);
                                $contenu .= '<p>Signature image not available.</p>';
                            }

                            $message->html($contenu);
                        });
                    } catch (\Exception $e) {
                        return response()->json(['error' => 'An error occurred while sending the email', 'message' => $e->getMessage()], 500);
                    }
                    $logmail = [];
                    $logmail['sujet'] = $request->sujet;
                    $logmail['contenu'] = $contenu;
                    $logmail['Destinataire'] = $contact->email;
                    $logmail['pdf'] = $pdfPath;
                    $logmail['ddp_nom'] = $ddp->nom;
                    $logmail['ddp_id'] = $ddp->id;
                    $logmail['societe_raison_sociale'] = $societe->raison_sociale;
                    $logmail['societe_id'] = $societe->id;
                    $logmail['contact_nom'] = $contact->nom;
                    $logmail['contact_id'] = $contact->id;
                    ModelChange::create([
                        'user_id' => Auth::id(),
                        'model_type' => 'Mail',
                        'before' => '',
                        'after' => $logmail,
                        'event' => 'creating',
                    ]);
                    $contacts_Already_sent[] = $contact->id;
                }
            }
        }
        $ddp->ddp_cde_statut_id = 2;
        $ddp->save();
        return redirect()->route('ddp.show', $ddp->id)->with('success', 'Les emails ont été envoyés avec succès');
    }
    public function skipMails($id)
    {
        $ddp = Ddp::findOrFail($id);
        $ddp->ddp_cde_statut_id = 2;
        $ddp->save();
        return redirect()->route('ddp.show', $ddp->id);
    }
    public function saveRetours(Request $request, $id)
    {
        $ddp = Ddp::findOrFail($id);
        if ($ddp->affaire && $ddp->affaire->statut === \App\Models\Affaire::STATUT_TERMINE) {
            abort(403, 'Impossible de modifier une demande de prix liée à une affaire terminée.');
        }
        $request->validate([
            'data' => 'required|string',
        ]);
        $data = array_map('str_getcsv', array_filter(explode("\r\n", $request->data), 'strlen'));
        array_shift($data);
        array_pop($data);
        $ddplignes = $ddp->ddpLigne->where('ligne_autre_id', null);

        $ddp_societes = $ddplignes->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                return $fournisseur->societe;
            });
        })->flatten()->unique('id');
        $data2 = [];
        // foreach ($ddp->ddpLigne as $ddpLigne) {

        //     $row = [];
        //     foreach ($ddp_societes as $societe) {
        //     $row[] = '';
        //     $row[] = '';
        //     }
        //     $data2[] = $row;
        // }
        $index0 = 0;
        foreach ($ddplignes as $indexviré => $ddpLigne) {
            $row = [];
            $index_societe = 0;
            foreach ($ddp_societes as $index1 => $societe) {
                $matiereid = $ddpLigne->matiere_id;
                $societeid = $societe->id;
                $matiere = Matiere::findOrFail($matiereid);
                $last_prix = $matiere->prixPourSociete($societeid)->first();

                // Récupération des valeurs
                $ref_fournisseur = $data[$index0][$index_societe] ?? null;
                $ref_fournisseur = str_replace(' ', '_', $ref_fournisseur);
                $newPrix = $data[$index0][$index_societe + 1] ?? null;
                // $newUnite = Unite::where('short', $data[$index0][$index_societe + 2] ?? '')->first()->id ?? null;
                $dateString = $data[$index0][$index_societe + 3] ?? '';

                // Traitement de la date
                $date = (!empty($dateString) && $dateString != 'UNDEFINED') ? Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d') : null;
                // Vérification et mise à jour ou insertion

                if ($newPrix != 'UNDEFINED') {
                    $SocieteMatiere = SocieteMatiere::updateOrCreate(
                        [
                            'societe_id' => $societeid,
                            'matiere_id' => $matiereid,
                        ],
                        [
                            'ref_externe' => $ref_fournisseur ?? null,
                        ]
                    );
                    if ($newPrix && $newPrix != 'UNDEFINED' && $last_prix != $newPrix) {

                        SocieteMatierePrix::updateOrCreate(
                            [
                                'societe_matiere_id' => $SocieteMatiere->id,
                                'ddp_ligne_fournisseur_id' => $ddpLigne->ddpLigneFournisseur->where('societe_id', $societeid)->first()->id ?? '2444',
                            ],
                            [
                                'prix_unitaire' => $newPrix ?? 'null',
                                'date' => now(),
                            ]
                        );
                    }
                }



                // if ($newPrix && $newPrix != 'UNDEFINED' && (!$existingFournisseur || $existingFournisseur->pivot->prix != $newPrix)) {
                //     $matiere->fournisseurs()->attach($societeid, [
                //         'ref_fournisseur' => $ref_fournisseur,
                //         'prix' => $newPrix,
                //         'unite_id' => $newUnite,
                //         'date_dernier_prix' => now(),
                //         'ddp_ligne_fournisseur_id' => $ddpLigne->ddpLigneFournisseur->where('societe_id', $societeid)->first()->id ?? null
                //     ]);
                //     return true;
                // } elseif ($existingFournisseur && $newPrix != 'UNDEFINED' && $newPrix != '') {
                //     $existingFournisseur->pivot->update([
                //         'ref_fournisseur' => $ref_fournisseur,
                //         'prix' => $newPrix,
                //         'unite_id' => $newUnite,
                //         'date_dernier_prix' => now()
                //     ]);
                //     return false;
                // }

                // Mise à jour de la date de livraison dans ddpLigneFournisseur
                $ddpLigneFournisseur = $ddpLigne->ddpLigneFournisseur->where('societe_id', $societeid)->first();
                if ($ddpLigneFournisseur && $date) {
                    $ddpLigneFournisseur->date_livraison = $date;
                    $ddpLigneFournisseur->save();
                }

                // Ajouter à la ligne de données pour exporter
                $row[] = $index0 . '-' . $societeid . '-' . $ref_fournisseur;
                $row[] = $index0 . '-' . $societeid . '-' . $newPrix;
                $row[] = $index0 . '-' . $societeid . '-' . '';
                $row[] = $index0 . '-' . $societeid . '-' . $date;
                $index_societe += 4;
            }
            $data2[] = $row;
            $index0 = $index0 + 1;
        }

        $data3[] = $data;
        $data3[] = $data2;
        return $data3;
        // Assuming you have some logic to save the data here

    }
    public function getRetours($id): array
    {
        $ddp = Ddp::findOrFail($id);
        $ddplignes = $ddp->ddpLigne->where('ligne_autre_id', null);
        $ddp_societes = $ddplignes->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                return $fournisseur->societe;
            });
        })->flatten()->unique('id');
        if ($ddp->ddp_cde_statut_id == 2) {


            $data = [];
            foreach ($ddplignes as $ddpLigne) {
                $row = [];
                foreach ($ddp_societes as $societe) {
                    $ddpLigneFournisseur = $ddpLigne->ddpLigneFournisseur->where('societe_id', $societe->id)->first();
                    if ($ddpLigneFournisseur) {
                        $prix = '';
                        $unite = $ddpLigneFournisseur->ddpLigne->matiere->unite->short;
                        if ($ddpLigneFournisseur->ddpLigne->matiere) {
                            $Societe_prix = $ddpLigneFournisseur->ddpLigne->matiere->prixPourSociete($societe->id)
                                ->orderBy('date', 'desc')
                                ->first();
                            if ($Societe_prix) {
                                $prix = $Societe_prix->prix_unitaire;
                            } else {
                                $prix = '';
                            }
                        } else {
                            $prix = '';
                        }
                        $date_livraison = $ddpLigneFournisseur->date_livraison ? Carbon::parse($ddpLigneFournisseur->date_livraison)->format('d/m/Y') : '';
                        $reference_fournisseur = $ddpLigneFournisseur->ddpLigne->matiere->societeMatiere($societe->id);
                        $reference_fournisseur = $reference_fournisseur ? $reference_fournisseur->ref_externe : '';
                        // dd($ddpLigneFournisseur->ddpLigne->matiere->id,[$societe->id,$reference_fournisseur]);
                        $row[] = $reference_fournisseur ?? '';
                        $row[] = $prix;
                        $row[] = $unite;
                        $row[] = $date_livraison;
                    } else {
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                    }
                }
                $data[] = $row;
            }
        } elseif ($ddp->ddp_cde_statut_id == 3) {
            $data = [];
            foreach ($ddplignes as $ddpLigne) {
                $row = [];
                foreach ($ddp_societes as $societe) {
                    $ddpLigneFournisseur = $ddpLigne->ddpLigneFournisseur->where('societe_id', $societe->id)->first();
                    if ($ddpLigneFournisseur) {
                        $prix = '';
                        if ($ddpLigneFournisseur->ddpLigne->matiere) {
                            $societeMatiere = $ddpLigneFournisseur->ddpLigne->matiere->prixPourSociete($societe->id)
                                ->where('ddp_ligne_fournisseur_id', $ddpLigneFournisseur->id)
                                ->orderBy('date', 'desc')
                                ->first();
                            if ($societeMatiere) {
                                $prix = $societeMatiere->prix_unitaire;
                                $unite = $ddpLigneFournisseur->ddpLigne->matiere->unite->short;
                            } else {
                                $prix = '';
                                $unite = '';
                            }
                        } else {
                            $prix = '';
                            $unite = '';
                        }
                        $date_livraison = $ddpLigneFournisseur->date_livraison ? Carbon::parse($ddpLigneFournisseur->date_livraison)->format('d/m/Y') : '';
                        if ($prix != '') {
                            if ($unite != '') {
                                $row[] = formatNumberArgent($prix) . '/' . $unite;
                            } else {
                                $row[] = formatNumberArgent($prix);
                            }
                            $row[] = formatNumberArgent($prix * $ddpLigne->quantite);
                        } else {
                            $row[] = '';
                            $row[] = '';
                        }
                        $row[] = $date_livraison;
                    } else {
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                    }
                }

                $data[] = $row;
            }
        }
        return $data ?? [];
    }
    public function terminer($id)
    {
        $ddp = Ddp::findOrFail($id);
        $ddp->ddp_cde_statut_id = 3;
        $ddp->save();
        return redirect()->route('ddp.show', $ddp->id);
    }
    public function annuler_terminer($id)
    {
        $ddp = Ddp::findOrFail($id);
        if ($ddp->ddp_cde_statut_id == 3) {
            $ddp->ddp_cde_statut_id = 2;
            $ddp->save();
        } else {
            return redirect()->route('ddp.show', $ddp->id)->with('error', 'Vous ne pouvez pas annuler une demande de prix qui n\'est pas terminée');
        }
        return redirect()->route('ddp.show', $ddp->id);
    }
    public function commander($id, $societe_contact_id)
    {
        $ddp = Ddp::findOrFail($id);
        $societe_contact = SocieteContact::findOrFail($societe_contact_id);
        $societe = $societe_contact->etablissement->societe;
        $commentaire = Commentaire::create(
            ['contenu' => '']
        );
        $cde = Cde::create([
            'code' => 'CDE-' . now()->format('y') . '-' . str_pad(Cde::whereYear('created_at', now()->year)->count() + 1, 4, '0', STR_PAD_LEFT),
            'nom' => 'Commande de ' . $ddp->code . ' ' . $societe->raison_sociale,
            'ddp_cde_statut_id' => 1,
            'ddp_id' => $ddp->id,
            'entite_id' => $ddp->entite_id,
            'user_id' => Auth::id(),
            'tva' => 20,
            'type_expedition_id' => 1,
            'condition_paiement_id' => 1,
            'show_ref_fournisseur' => true,
            'commentaire_id' => $commentaire->id,
        ]);
        DB::table('cde_societe_contacts')->insert([
            'cde_id' => $cde->id,
            'societe_contact_id' => $societe_contact_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $poste = 0;
        $ddpLignes = $ddp->ddpLigne->where('ligne_autre_id', null);
        foreach ($ddpLignes as $ddpLigne) {
            foreach ($ddpLigne->ddpLigneFournisseur as $ddpLigneFournisseur) {
                if ($ddpLigneFournisseur->societe_id == $societe->id) {
                    $poste++;
                    CdeLigne::create([
                        'cde_id' => $cde->id,
                        'poste' => $poste,
                        'matiere_id' => $ddpLigne->matiere_id,
                        'ref_interne' => $ddpLigne->matiere->ref_interne,
                        'designation' => $ddpLigne->matiere->designation,
                        'quantite' => $ddpLigne->quantite,
                        'ref_fournisseur' => $ddpLigne->matiere->societeMatiere($societe->id)->ref_externe ?? null,
                        'prix_unitaire' => $ddpLigne->matiere->getLastPrice($societe->id) ? $ddpLigne->matiere->getLastPrice($societe->id)->prix_unitaire : null,
                        'date_livraison' => $ddpLigneFournisseur->date_livraison,
                    ]);
                }
            }
        }
        return redirect()->route('cde.show', $cde->id);
    }
    /**
     * Retourne le prochain code de DDP
     * @param mixed $entite_id
     * @return string
     */
    public function getLastCode($entite_id)
    {
        $entite = Entite::findOrFail($entite_id);
        $lastcode = DDP::where('code', 'LIKE', 'DDP-' . date('y') . '%')
            ->where('entite_id', $entite->id)
            ->orderBy('code', 'desc')
            ->first();
        $lastnumber = $lastcode ? intval(substr($lastcode->code, 8, 4)) : '0000';
        $lastnumber = str_pad($lastnumber + 1, 4, '0', STR_PAD_LEFT);
        if ($entite->id == 1) {
            $entite_code = '';
        } elseif ($entite->id == 2) {
            $entite_code = 'AV';
        } elseif ($entite->id == 3) {
            $entite_code = 'AMB';
        }
        return response()->json(['code' => $lastnumber, 'entite_code' => $entite_code]);
    }

    public function updateCommentaire(Request $request, $id)
    {
        $ddp = Ddp::find($id);
        if ($ddp) {
            if ($ddp->affaire && $ddp->affaire->statut === \App\Models\Affaire::STATUT_TERMINE) {
                return response()->json(['error' => 'Impossible de modifier le commentaire d\'une demande de prix liée à une affaire terminée.'], 403);
            }
            // Trouve le commentaire lié à la demande de prix
            $commentaire = $ddp->commentaire;
            if ($commentaire) {
                if ($commentaire->contenu == $request->commentaire) {
                    return response()->json(['message' => 'Commentaire inchangé'], 200);
                }
                // Met à jour le commentaire avec la nouvelle valeur
                $commentaire->contenu = $request->commentaire;
                $commentaire->save();
                return response()->json(['message' => 'Commentaire mis à jour avec succès'], 200);
            } else {
                // Si la demande de prix n'a pas encore de commentaire, on en crée un
                $commentaire = new Commentaire();
                $commentaire->contenu = $request->commentaire;
                $ddp->commentaire()->save($commentaire);
                return response()->json(['message' => 'Commentaire créé avec succès'], 201);
            }
        }
        return response()->json(['message' => 'Demande de prix introuvable'], 404);
    }

    public function annuler($id)
    {
        $ddp = Ddp::findOrFail($id);
        // Store the previous status but don't save status 4 (cancelled) as the old_statut
        if ($ddp->ddp_cde_statut_id != 4) {
            $ddp->old_statut = $ddp->ddp_cde_statut_id;
        }
        $ddp->ddp_cde_statut_id = 4; // 4 is the status for cancelled DDPs
        $ddp->save();

        return redirect()->route('ddp.show', $ddp->id)->with('success', 'Demande de prix annulée avec succès');
    }

    public function reprendre($id)
    {
        $ddp = Ddp::findOrFail($id);
        $ddp->ddp_cde_statut_id = $ddp->old_statut;
        $ddp->save();

        return redirect()->route('ddp.show', $ddp->id)->with('success', 'Demande de prix reprise avec succès');
    }
}

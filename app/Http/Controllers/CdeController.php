<?php

namespace App\Http\Controllers;

use App\Models\Cde;
use App\Models\CdeLigne;
use App\Models\CdeNote;
use App\Models\Commentaire;
use App\Models\ConditionPaiement;
use App\Models\DdpCdeStatut;
use App\Models\Entite;
use App\Models\Famille;
use App\Models\Mailtemplate;
use App\Models\ModelChange;
use App\Models\Societe;
use App\Models\SocieteMatiere;
use App\Models\SocieteMatierePrix;
use App\Models\TypeExpedition;
use App\Models\Unite;
use App\Models\User;
use App\Services\StockService;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Log;
use Mail;
use Response;
use Storage;

class CdeController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    public function indexColCdeSmall()
    {
        return $this->indexColCde(true);
    }
    public function indexColCde($isSmall = false)
    {
        $limit = $isSmall ? 5 : 30;

        $cdes = Cde::where('cdes.nom', '!=', 'undefined') // Préfixer avec le nom de la table
            ->orderBy('ddp_cde_statut_id', 'asc')
            ->orderBy('created_at', 'desc')
            ->take($limit)->get();

        if ($cdes->count() < ($isSmall ? 5 : 30)) {
            $existingIds = $cdes->pluck('id')->toArray();
            $additionalCdes = Cde::where('cdes.nom', '!=', 'undefined') // Préfixer avec le nom de la table
                ->whereNotIn('id', $existingIds)
                ->orderBy('created_at', 'desc')
                ->take(($isSmall ? 5 : 30) - $cdes->count())
                ->get();

            $cdes = $cdes->concat($additionalCdes);
        }
        $cdes->load('user');
        $cdes->load('ddpCdeStatut');
        return view('ddp_cde.cde.index_col', compact('cdes', 'isSmall'));
    }
    public function index(Request $request)
    {
        // Validation des entrées
        $request->validate([
            'search' => 'nullable|string|max:255',
            'statut' => 'nullable|integer|exists:ddp_cde_statuts,id',
            'nombre' => 'nullable|integer|min:1|',
            'societe' => 'nullable|integer|exists:societes,id',
            'sort' => 'nullable|string|in:code,created_at,nom,user,statut',
            'direction' => 'nullable|string|in:asc,desc',
        ]);
        // Lecture des entrées avec des valeurs par défaut
        $search = $request->input('search');
        $statut = $request->input('statut');
        $quantite = $request->input('nombre', 100);
        $societe = $request->input('societe');
        $sort = $request->input('sort', 'code'); // Changé de 'created_at' à 'code'
        $direction = $request->input('direction', 'desc');

        // Construire la requête de base
        $query = Cde::query()
            ->where('cdes.nom', '!=', 'undefined') // Préfixer avec le nom de la table
            ->with(['entite', 'user', 'ddpCdeStatut']) // Charger les relations nécessaires
            ->when($search, function ($query, $search) {
                // Diviser la recherche en termes individuels
                $terms = explode(' ', $search);

                if (count($terms) == 1) {
                    // Recherche simple avec un seul terme
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('cdes.nom', 'ILIKE', "%{$search}%")
                            ->orWhere('cdes.code', 'ILIKE', "%{$search}%")
                            ->orWhereHas('user', function ($subQuery) use ($search) {
                                $subQuery->where('first_name', 'ILIKE', "%{$search}%")
                                    ->orWhere('last_name', 'ILIKE', "%{$search}%");
                            })
                            ->orWhereHas('cdeLignes', function ($subQuery) use ($search) {
                                $subQuery->where('ref_interne', 'ILIKE', "%{$search}%")
                                    ->orWhere('ref_fournisseur', 'ILIKE', "%{$search}%")
                                    ->orWhere('designation', 'ILIKE', "%{$search}%");
                            });
                    });
                } else {
                    // Recherche avancée avec plusieurs termes
                    $query->where(function ($mainQuery) use ($terms) {
                        foreach ($terms as $term) {
                            $mainQuery->where(function ($subQuery) use ($term) {
                                $subQuery->where('cdes.nom', 'ILIKE', "%{$term}%")
                                    ->orWhere('cdes.code', 'ILIKE', "%{$term}%")
                                    ->orWhereHas('user', function ($subQuery) use ($term) {
                                        $subQuery->where('first_name', 'ILIKE', "%{$term}%")
                                            ->orWhere('last_name', 'ILIKE', "%{$term}%");
                                    })
                                    ->orWhereHas('cdeLignes', function ($subQuery) use ($term) {
                                        $subQuery->where('ref_interne', 'ILIKE', "%{$term}%")
                                            ->orWhere('ref_fournisseur', 'ILIKE', "%{$term}%")
                                            ->orWhere('designation', 'ILIKE', "%{$term}%");
                                    });
                            });
                        }
                    });
                }
            })
            ->when($statut, function ($query, $statut) {
                $query->where('ddp_cde_statut_id', $statut);
            });

        if ($societe) {
            $query->whereHas('societeContacts.etablissement.societe', function ($q) use ($societe) {
                $q->where('id', $societe);
            });
        }

        // Appliquer le tri avec groupement par entité
        switch ($sort) {
            case 'code':
                $query->orderBy('cdes.entite_id', 'asc')
                    ->orderBy('cdes.code', $direction);
                break;
            case 'nom':
                $query->orderBy('cdes.entite_id', 'asc')
                    ->orderBy('cdes.nom', $direction);
                break;
            case 'user':
                $query->join('users', 'cdes.user_id', '=', 'users.id')
                    ->orderBy('cdes.entite_id', 'asc')
                    ->orderBy('users.first_name', $direction)
                    ->orderBy('users.last_name', $direction)
                    ->select('cdes.*');
                break;
            case 'statut':
                $query->join('ddp_cde_statuts', 'cdes.ddp_cde_statut_id', '=', 'ddp_cde_statuts.id')
                    ->orderBy('cdes.entite_id', 'asc')
                    ->orderBy('ddp_cde_statuts.nom', $direction)
                    ->select('cdes.*');
                break;
            case 'created_at':
                $query->orderBy('cdes.entite_id', 'asc')
                    ->orderBy('cdes.created_at', $direction);
                break;
            default:
                // Tri par défaut : entité puis code de commande
                $query->orderBy('cdes.entite_id', 'asc')
                    ->orderBy('cdes.ddp_cde_statut_id', 'asc')
                    ->orderBy('cdes.code', 'asc');
                break;
        }

        // Récupérer les résultats paginés
        $cdes = $query->paginate($quantite);

        // Grouper par entité pour la vue - convertir d'abord en collection puis grouper
        $cdesGrouped = $cdes->getCollection()->groupBy('entite.name');

        // Récupérer les statuts pour le filtre
        $cde_statuts = DdpCdeStatut::all();
        // Récupérer toutes les sociétés distinctes liées à toutes les CDE
        $societes = collect();
        foreach (Cde::where('nom', '!=', 'undefined')->get() as $cde) {
            if ($cde->societe) {
                $societes = $societes->push($cde->societe);
            }
        }
        $societes = $societes->groupBy('id')->map(function ($group) {
            $societe = $group->first();
            $societe->usage_count = $group->count();
            $societe->raison_sociale .= ' (' . $societe->usage_count . ')';
            return $societe;
        })->sortByDesc('usage_count')->values();
        // Retourner la vue avec les données
        return view('ddp_cde.cde.index', compact('cdes',  ['cde_statuts', 'cdesGrouped', 'societes', 'sort', 'direction']));
    }

    public function create(Request $request)
    {
        if ($request->has('affaire_id')) {
            $affaire = \App\Models\Affaire::find($request->input('affaire_id'));
            if ($affaire && ($affaire->statut === \App\Models\Affaire::STATUT_TERMINE || $affaire->statut === \App\Models\Affaire::STATUT_ARCHIVE)) {
                return redirect()->back()->with('error', 'Impossible de créer une commande pour une affaire terminée ou archivée.');
            }
        }

        Cde::where('nom', 'undefined')->delete();
        $lastCde = Cde::latest()->first();
        $code = $lastCde ? $lastCde->code : 'CDE-' . now()->format('y') . '-0000';
        $code = explode('-', $code);
        $code = $code[1] + 1;
        $commentaire_id = Commentaire::create([
            'contenu' => '',
        ])->id;

        // Récupérer le premier statut disponible ou créer le statut par défaut
        $defaultStatut = DdpCdeStatut::orderBy('id')->first();
        if (!$defaultStatut) {
            $defaultStatut = DdpCdeStatut::create([
                'nom' => 'En attente',
                'couleur' => '#F4C27F',
                'couleur_texte' => '#5A3E1B'
            ]);
        }
        $statutId = $defaultStatut->id;

        // Récupérer la première entité disponible ou créer l'entité par défaut
        $defaultEntite = Entite::orderBy('id')->first();
        if (!$defaultEntite) {
            $defaultEntite = Entite::create([
                'name' => 'Entité par défaut',
                'adresse' => 'Adresse inconnue',
                'ville' => 'Ville inconnue',
                'code_postal' => '00000',
                'tel' => '00 00 00 00 00',
                'siret' => '00000000000000',
                'rcs' => 'Inconnu',
                'numero_tva' => 'FR00000000000',
                'code_ape' => '0000A',
                'logo' => '',
                'horaires' => '',
            ]);
        }
        $entiteId = $defaultEntite->id;

        // Récupérer le premier type d'expédition disponible ou créer le type par défaut
        $defaultTypeExpedition = TypeExpedition::orderBy('id')->first();
        if (!$defaultTypeExpedition) {
            $defaultTypeExpedition = TypeExpedition::create([
                'nom' => 'Livraison',
                'short' => 'livraison'
            ]);
        }
        $typeExpeditionId = $defaultTypeExpedition->id;

        $cde = Cde::create([
            'code' => 'undefined',
            'nom' => 'undefined',
            'ddp_cde_statut_id' => $statutId,
            'entite_id' => $entiteId,
            'user_id' => Auth::id(),
            'tva' => 20,
            'type_expedition_id' => $typeExpeditionId,
            'show_ref_fournisseur' => true,
            'commentaire_id' => $commentaire_id,
            'affaire_id' => $request->input('affaire_id'),
        ]);
        $cdeid =  $cde->id;

        // Si on vient de la vérification des devis avec une matière à préselectionner
        if ($request->has('matiere_id') && $request->has('quantite')) {
            // Utiliser put() au lieu de flash() pour que la session persiste après redirect
            session()->put('preselect_matiere_' . $cdeid, [
                'matiere_id' => $request->input('matiere_id'),
                'quantite' => $request->input('quantite')
            ]);
        }

        return redirect()->route('cde.show', $cdeid);
    }
    public function show($id, $show_stock = false)
    {
        $cde = Cde::findOrFail($id);
        if ($cde->ddpCdeStatut->id == 1) {
            $cdeid =  $cde->id;
            $familles = Famille::all();
            $unites = Unite::all();
            $entites = Entite::all();
            $societes = Societe::whereIn('societe_type_id', [2, 3])->get();
            $showRefFournisseur = $cde->show_ref_fournisseur;
            $entite_code = Entite::findOrFail($cde->entite_id)->id;
            $lastcode = CDE::where('code', 'LIKE', 'CDE-' . date('y') . '%')
                ->where('entite_id', $cde->entite_id)
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
            if ($cde->code == 'undefined') {
                $cde->code = "CDE-" . date('y') . "-" . $newNumber . $entite_code;
                $cde->save();
            }

            // Récupérer les données de présélection si disponibles
            $preselectMatiere = session()->get('preselect_matiere_' . $cdeid);
            // Supprimer la session après l'avoir récupérée
            if ($preselectMatiere) {
                session()->forget('preselect_matiere_' . $cdeid);
            }

            return view(
                'ddp_cde.cde.create',
                [
                    'cde' => $cde,
                    'familles' => $familles,
                    'unites' => $unites,
                    'entites' => $entites,
                    'cdeid' => $cdeid,
                    'societes' => $societes,
                    'showRefFournisseur' => $showRefFournisseur,
                    'entite_code' => $entite_code,
                    'preselectMatiere' => $preselectMatiere,
                ]
            );
        } elseif ($cde->ddpCdeStatut->id == 2) {
            $cdeid =  $cde->id;
            $showRefFournisseur = $cde->show_ref_fournisseur;
            $typeExpedition = TypeExpedition::all()->pluck('short');
            $data = $this->getRetours($cdeid);
            return view('ddp_cde.cde.retours', compact('cde', ['data', 'showRefFournisseur', 'typeExpedition']));
        } elseif ($cde->ddpCdeStatut->id == 3 || $cde->ddpCdeStatut->id == 5) {
            $cdeid =  $cde->id;
            $showRefFournisseur = $cde->show_ref_fournisseur;
            $pdfcommande = $this->pdf($cdeid);
            // Récupérer les mouvements de stock et les grouper par matière
            $changements_stock = $cde->cdeLignes()
                ->where('is_stocke', '!=', null)
                ->with(['mouvementsStock' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }])
                ->get();
            return view('ddp_cde.cde.show', [
                'cde' => $cde,
                'showRefFournisseur' => $showRefFournisseur,
                'pdfcommande' => $pdfcommande,
                'changements_stock' => $changements_stock,
                'show_stock' => $show_stock,
            ]);
        } elseif ($cde->ddpCdeStatut->id == 4) {
            $cdeid =  $cde->id;
            $showRefFournisseur = $cde->show_ref_fournisseur;
            return view('ddp_cde.cde.show_annule', [
                'cde' => $cde,
                'showRefFournisseur' => $showRefFournisseur,
            ]);
        }
    }


    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'cde_id' => 'required|integer|exists:cdes,id',
            'entite_id' => 'required|integer|exists:entites,id',
            'code' => 'required|string|max:7',
            'show_ref_fournisseur' => 'required|boolean',
            'contact_id' => 'required|string',
            'nom' => 'required|string|max:255',
            'matieres' => 'nullable|array',
            'matieres.*.id' => 'nullable|integer',
            'matieres.*.quantite' => 'required|numeric|min:0',
            'matieres.*.refInterne' => 'nullable|string|max:255',
            'matieres.*.refFournisseur' => 'nullable|string|max:255',
            'matieres.*.designation' => 'required|string|max:255',
            'matieres.*.sousLigne' => 'nullable|string|max:255',
            'matieres.*.prix' => 'required|numeric|min:0',
            // 'matieres.*.unite_id' => 'required|integer|exists:unites,id',
            'matieres.*.date' => 'nullable|date',
            'matieres.*.ligne_autre_id' => 'nullable|string',
        ]);
        DB::beginTransaction();
        if ($validator->fails()) {
            DB::rollBack();
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $entite_code = Entite::findOrFail($request->entite_id)->id;
        if ($entite_code == 1) {
            $entite_code = '';
        } elseif ($entite_code == 2) {
            $entite_code = 'AV';
        } elseif ($entite_code == 3) {
            $entite_code = 'AMB';
        }
        if ($request->code && preg_match('/^\d{1,4}[A-Za-z]{0,3}$/', $request->code)) {
            // Sépare la partie numérique et la partie lettres
            preg_match('/^(\d{1,4})([A-Za-z]{0,3})$/', $request->code, $matches);
            $numericPart = str_pad($matches[1], 4, '0', STR_PAD_LEFT);
            $lettersPart = isset($matches[2]) ? strtoupper($matches[2]) : '';
            $code = $numericPart . $lettersPart;
        } else {
            DB::rollBack();
            return response()->json(['error' => 'Invalid code format'], 400);
        }
        $cde = Cde::findOrFail($request->input('cde_id'));
        if ($cde->affaire && $cde->affaire->statut === \App\Models\Affaire::STATUT_TERMINE) {
            DB::rollBack();
            return response()->json(['error' => 'Impossible de modifier une commande liée à une affaire terminée.'], 403);
        }
        // Parse the JSON string and attach each societe contact to the CDE
        $contactIds = json_decode($request->input('contact_id'));
        if (!empty($contactIds)) {
            // Delete existing relations first
            DB::table('cde_societe_contacts')->where('cde_id', $cde->id)->delete();

            // Attach each contact, skipping duplicates
            foreach ($contactIds as $contactId) {
                $exists = DB::table('cde_societe_contacts')
                    ->where('cde_id', $cde->id)
                    ->where('societe_contact_id', $contactId)
                    ->exists();

                if (!$exists) {
                    DB::table('cde_societe_contacts')->insert([
                        'cde_id' => $cde->id,
                        'societe_contact_id' => $contactId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        } else {
            DB::rollBack();
            return response()->json(['error' => 'il n\'y a pas de destinataire sélectionné'], 422);
        }
        $cde->entite_id = $request->input('entite_id');
        $cde->show_ref_fournisseur = $request->input('show_ref_fournisseur');
        $cde->nom = $request->input('nom');
        // $cde->total_ht = $request->input('total_ht');
        $cde->code = "CDE-" . date('y') . "-" . $code . $entite_code;
        $cde->save();
        $poste = 1;
        $cde->cdeLignes()->delete();

        if (empty($request->input('matieres'))) {
            DB::commit();
            return response()->json(['success' => true]);
        }
        foreach ($request->input('matieres') as $matiere) {

            if (isset($matiere['ligne_autre_id'])) {
                $cde->cdeLignes()->updateOrCreate(
                    ['ligne_autre_id' => $matiere['ligne_autre_id']],
                    [
                        'poste' => $poste++,
                        'quantite' => $matiere['quantite'],
                        'ref_interne' => $matiere['refInterne'] ?? null,
                        'ref_fournisseur' => $matiere['refFournisseur'] ?? null,
                        'designation' => $matiere['designation'] ?? null,
                        'sous_ligne' => $matiere['sousLigne'],
                        'prix_unitaire' => $matiere['prix'],
                        'prix' => $matiere['prix'] * $matiere['quantite'],
                        'date_livraison' => $matiere['date'] ?? null,
                    ]
                );
            } else {
                $cde->cdeLignes()->create([
                    'poste' => $poste++,
                    'matiere_id' => $matiere['id'],
                    'quantite' => $matiere['quantite'],
                    'sous_ligne' => $matiere['sousLigne'],
                    'ref_interne' => $matiere['refInterne'] ?? null,
                    'ref_fournisseur' => $matiere['refFournisseur'] ?? null,
                    'designation' => $matiere['designation'] ?? null,
                    'prix_unitaire' => $matiere['prix'],
                    'prix' => $matiere['prix'] * $matiere['quantite'],
                    // 'unite_id' => $matiere['unite_id'],
                    'date_livraison' => $matiere['date'] ?? null,
                ]);
            }
        }
        DB::commit();
        return response()->json(['success' => true]);
    }

    public function destroy($id): RedirectResponse
    {

        try {
            $cde = Cde::findOrFail($id);
            if ($cde->affaire && $cde->affaire->statut === \App\Models\Affaire::STATUT_TERMINE) {
                return back()->with('error', 'Impossible de supprimer une commande liée à une affaire terminée.');
            }
            if ($cde->ddp_id != null) {
                $dppid = $cde->ddp_id;
                $cde->delete();
                return redirect()->route('ddp.show', $dppid);
            } else {
                $cde->delete();
                return redirect()->route('ddp_cde.index');
            }
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la commande : ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la commande.');
        }
    }
    public function reset($id): RedirectResponse
    {
        $cde = Cde::findOrFail($id);
        $cde->delete();
        return redirect()->route('cde.create');
    }
    public function validation($id): View
    {
        $cde = Cde::findOrFail($id);
        $users = User::all();
        $entite = Entite::where('id', $cde->entite_id)->first();
        $showRefFournisseur = $cde->show_ref_fournisseur;
        $typesExpedition = TypeExpedition::all();
        $conditionsPaiement = ConditionPaiement::all();
        $societe_id = $cde->societe?->id;
        $cde_notes = CdeNote::where('entite_id', $cde->entite_id)->get();
        $affaires = \App\Models\Affaire::where('statut', '!=', \App\Models\Affaire::STATUT_TERMINE)
            ->orderBy('created_at', 'desc')
            ->get();
        $total_ht = 0;
        foreach ($cde->cdeLignes as $ligne) {
            $total_ht += $ligne->prix_unitaire * $ligne->quantite;
        }
        $cde->total_ht = $total_ht;
        $cde->total_ttc = ($cde->total_ht + ($cde->frais_de_port ?? 0) + ($cde->frais_divers ?? 0)) * (1 + ($cde->tva / 100));
        $cde->save();
        //verifier si
        if ($showRefFournisseur == true) {
            $listeChangement = [];
            foreach ($cde->cdeLignes as $ligne) {
                if ($ligne->ligne_autre_id == null) {
                    $ligne->prix = $ligne->prix_unitaire * $ligne->quantite;
                    $ligne->save();
                    $societe_matiere = $ligne->matiere->societeMatiere($societe_id);
                    $ref_externe = $societe_matiere?->ref_externe ?? null;
                    if ($ligne->ref_fournisseur != null && $ligne->ref_fournisseur != '' && $ligne->ref_fournisseur != $ref_externe) {
                        $listeChangement[] = [
                            'id' => $ligne->id,
                            'ref_interne' => $ligne->matiere->ref_interne,
                            'ref_fournisseur' => $ligne->ref_fournisseur,
                            'ref_externe' => $ref_externe,
                            'designation' => $ligne->designation,
                            'societe_matiere_id' => $societe_matiere->id ?? null,
                        ];
                    }
                }
            }
        } else {
            $listeChangement = false;
        }
        return view('ddp_cde.cde.validation', compact('cde', 'users', 'entite', 'showRefFournisseur', 'typesExpedition', 'conditionsPaiement', 'listeChangement', 'cde_notes', 'affaires'));
    }

    public function validate(Request $request, $id)
    {
        $cde = Cde::findOrFail($id);
        $request->validate([
            'affaire_id' => 'nullable|integer|exists:affaires,id',
            'numero_devis' => 'nullable|string|max:255',
            'affaire_suivi_par' => 'nullable|integer',
            'acheteur_id' => 'nullable|integer',
            'afficher_destinataire' => 'nullable',
            'tva' => 'required|numeric|min:0',
            'horaires' => 'nullable|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'type_expedition_id' => 'required|integer|exists:type_expeditions,id',
            'condition_paiement_id' => 'required|integer',
            'condition_paiement_text' => 'nullable|string|max:255',
            'frais_de_port' => 'nullable|numeric|min:0',
            'frais_divers' => 'nullable|numeric|min:0',
            'frais_divers_texte' => 'nullable|string|max:255',
            'enregistrer_changement' => 'nullable',
            'cdenotes' => 'nullable|array',
            'custom_note' => 'nullable|string|max:255',
            'save_custom_note' => 'nullable|string|max:255',
            'quick_save' => 'required|string|max:6',
        ]);
        $type_expedition_id = $request->input('type_expedition_id');
        if ($type_expedition_id == 1) {
            $adresse['horaires'] = $request->input('horaires');
            $adresse['adresse'] = $request->input('adresse');
            $adresse['ville'] = $request->input('ville');
            $adresse['code_postal'] = $request->input('code_postal');
            $adresse['pays'] = $request->input('pays');
            $adresse = json_encode($adresse);
        } else {
            $adresse = null;
        }
        if ($request->input('condition_paiement_id') == 0) {

            if ($request->input('condition_paiement_text') == null || $request->input('condition_paiement_text') == '') {
                return back()->with('error', 'Veuillez saisir une condition de paiement');
            }
            $condition_paiement = ConditionPaiement::create([
                'nom' => $request->input('condition_paiement_text')
            ]);
            $condition_paiement_id = $condition_paiement->id;
        } else {
            $condition_paiement_id = $request->input('condition_paiement_id');
        }

        if ($request->cdenotes) {
            $cde->cdeNotes()->detach();
            foreach ($request->cdenotes as $cdenote) {
                $cde_note = CdeNote::findOrFail($cdenote);
                $cde->cdeNotes()->attach($cde_note);
            }
        }
        if ($request->save_custom_note && $request->save_custom_note == 'on' && !empty($request->custom_note)) {
            CdeNote::create([
                'contenu' => $request->custom_note,
                'ordre' => CdeNote::where('entite_id', $request->entite_id)->count(),
                'entite_id' => $cde->entite_id,
                'is_checked' => false,
            ]);
        } else {
        }
        $cde->custom_note = $request->custom_note ?? null;
        $cde->affaire_id = $request->input('affaire_id') ?? null;
        $cde->devis_numero = $request->input('numero_devis') ?? null;
        $cde->affaire_suivi_par_id = $request->input('affaire_suivi_par') ?? null;
        $cde->acheteur_id = $request->input('acheteur_id') ?? null;
        $cde->afficher_destinataire = $request->input('afficher_destinataire') ? true : false;
        $total_ht = 0;
        foreach ($cde->cdeLignes as $ligne) {
            $total_ht += $ligne->prix_unitaire * $ligne->quantite;
        }
        $cde->total_ht = $total_ht;
        $cde->tva = $request->input('tva');
        $cde->adresse_livraison = $adresse;
        $cde->type_expedition_id = $request->input('type_expedition_id');
        $cde->condition_paiement_id = $condition_paiement_id;
        $cde->frais_de_port = $request->input('frais_de_port') ?? 0;
        $cde->frais_divers = $request->input('frais_divers') ?? 0;
        $cde->frais_divers_texte = $request->input('frais_divers_texte') ?? null;
        $cde->total_ttc = ($cde->total_ht + $cde->frais_de_port + $cde->frais_divers) * (1 + ($cde->tva / 100));
        $cde->save();
        foreach ($cde->cdeLignes as $ligne) {
            $ligne->ddp_cde_statut_id = 2;
            $ligne->type_expedition_id = $request->input('type_expedition_id');
            $ligne->save();
        }
        if ($request->enregistrer_changement && $cde->show_ref_fournisseur == true) {
            $societe_id = $cde->societe->id;
            foreach ($cde->cdeLignes->whereNull('ligne_autre_id') as $ligne) {
                $ligne->prix = $ligne->prix_unitaire * $ligne->quantite;
                $ligne->save();
                $societe_matiere = null;
                if ($ligne->matiere) {
                    $societe_matiere = $ligne->matiere->societeMatiere($societe_id) ?? null;
                }
                if (!$societe_matiere) {
                    //on create un nouveau societe_matiere
                    $societe_matiere = SocieteMatiere::create([
                        'societe_id' => $societe_id,
                        'matiere_id' => $ligne->matiere_id,
                        'ref_externe' => null,
                    ]);
                }
                $ref_externe = $societe_matiere->ref_externe ?? null;
                if ($ligne->ref_fournisseur != null && $ligne->ref_fournisseur != '' && $ligne->ref_fournisseur != $ref_externe) {
                    $societe_matiere->ref_externe = $ligne->ref_fournisseur;
                    $societe_matiere->save();
                }
            }
        }
        if ($request->quick_save && $request->quick_save == 'true') {
            return redirect()->route('cde.validation', $cde->id);
        }
        $pdf = $this->pdf($cde->id);
        $this->pdf($cde->id, true);
        $mailtemplate = Mailtemplate::where('nom', 'cde')->first();
        $mailtemplate->sujet = str_replace('{code_cde}', $cde->code, $mailtemplate->sujet);
        $cde->ddp_cde_statut_id = 2;
        $cde->save();
        return view('ddp_cde.cde.pdf_preview', compact('cde', ['pdf', 'mailtemplate']));
    }
    public function cancelValidate($id)
    {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 1;
        $cde->changement_livraison = null;
        $cde->save();
        return redirect()->route('cde.validation', $cde->id);
    }
    public function pdf($cde_id, $sans_prix = false)
    {
        $cde = Cde::findOrFail($cde_id)->load('cdeLignes', 'cdeLignes.matiere');
        $contact = $cde->societeContacts->first();
        $lignes = $cde->cdeLignes;
        $etablissement = $cde->etablissement;
        $afficher_destinataire = $cde->afficher_destinataire;
        $fileName = $cde->code . '.pdf';
        $entite = $cde->entite;
        $showRefFournisseur = $cde->show_ref_fournisseur;
        $cde_notes = $cde->cdeNotes;
        $cde->save();
        $pdf = app('dompdf.wrapper');
        $pdf->loadView(
            'ddp_cde.cde.pdf',
            [
                'etablissement' => $etablissement,
                'contact' => $contact,
                'cde' => $cde,
                'lignes' => $lignes,
                'afficher_destinataire' => $afficher_destinataire,
                'entite' => $entite,
                'showRefFournisseur' => $showRefFournisseur,
                'sans_prix' => $sans_prix,
                'cde_notes' => $cde_notes,
            ]
        );
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
        $year = explode('-', $cde->code)[1];
        if ($sans_prix) {
            $fileName = 'sans_prix_' . $fileName;
        }
        Storage::put('CDE/' . $year . '/' . $fileName, $pdf->output());
        $path = Storage::path('CDE/' . $year . '/' . $fileName);
        return $fileName;
    }
    public function showPdf($cde, $dossier, $path)
    {
        $path = 'CDE/' . $dossier . '/' . $path;
        $file = Storage::get($path);
        $type = 'application/pdf';
        $response = Response::make($file, 200);
        $response->header('Content-Type', $type);
        return $response;
    }
    public function pdfDownloadSansPrix($id)
    {
        $cde = Cde::findOrFail($id);
        $cdeAnnee = explode('-', $cde->code)[1];
        $pdfPath = 'CDE/' . $cdeAnnee . '/sans_prix_' . $cde->code . '.pdf';
        if (!Storage::exists($pdfPath)) {
            return redirect()->back()->with('error', 'PDF file not found');
        }

        return response()->download(storage_path('app/private/' . $pdfPath));
    }
    public function downloadPdfs($cde_id)
    {
        $cde = Cde::findOrFail($cde_id);
        $cdeAnnee = explode('-', $cde->code)[1];
        $pdfPath = 'CDE/' . $cdeAnnee . '/' . $cde->code . '.pdf';

        if (!Storage::exists($pdfPath)) {
            return redirect()->back()->with('error', 'Aucun fichier à télécharger');
        }

        return response()->download(storage_path('app/private/' . $pdfPath));
    }
    public function sendMails(Request $request, $id)
    {
        $cde = Cde::findOrFail($id);
        $request->validate([
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        $contenu = str_replace("CHEVRON-GAUCHE", "<", $request->contenu);
        $contenu = str_replace("CHEVRON-DROIT", ">", $contenu);

        // Récupérer l'utilisateur connecté
        $currentUser = Auth::user();
        $senderEmail = $currentUser->email;
        $senderName = $currentUser->getName();

        // Ajouter l'information de l'expéditeur au début du contenu du mail
        $senderInfo = '<div style="margin-bottom: 20px; padding: 10px; background-color: #f3f4f6; border-left: 4px solid #3b82f6; font-size: 14px;">';
        $senderInfo .= '<strong>Envoyé par :</strong> ' . htmlspecialchars($senderName) . ' (<a href="mailto:' . htmlspecialchars($senderEmail) . '">' . htmlspecialchars($senderEmail) . '</a>)';
        $senderInfo .= '</div>';

        $contenu = $senderInfo . $contenu;

        $cdeAnnee = explode('-', $cde->code)[1];
        $pdfFileName = "{$cde->code}.pdf";
        $pdfPath = storage_path("app/private/CDE/{$cdeAnnee}/{$pdfFileName}");

        if (!file_exists($pdfPath)) {
            return response()->json(['error' => 'PDF file not found'], 404);
        }

        $contacts = $cde->societeContacts;

        if ($contacts->isEmpty()) {
            return response()->json(['error' => 'Aucun contact trouvé pour cette commande'], 404);
        }

        $primaryContact = $contacts->first();
        $ccContacts = $contacts->slice(1)->pluck('email')->toArray();
        $signaturePath = storage_path('app/private/signature/signature.png'); // Assurez-vous que le chemin est correct

        try {
            Mail::send([], [], function ($message) use ($request, $primaryContact, $ccContacts, $pdfPath, $signaturePath, &$contenu, $senderEmail, $senderName) {
                $message->to($primaryContact->email)
                    ->cc($ccContacts)
                    ->subject($request->sujet)
                    ->from($senderEmail, $senderName)
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

        $logmail = [
            'sujet' => $request->sujet,
            'contenu' => $contenu,
            'Expéditeur' => $senderName . ' <' . $senderEmail . '>',
            'Destinataire' => $primaryContact->email,
            'cc' => implode(', ', $ccContacts),
            'pdf' => $pdfPath,
            'cde_nom' => $cde->nom,
            'cde_id' => $cde->id,
            'societe_raison_sociale' => $cde->societe->raison_sociale,
            'societe_id' => $cde->societe->id,
            'contact_nom' => $primaryContact->nom,
            'contact_id' => $primaryContact->id,
        ];

        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Mail',
            'before' => '',
            'after' => $logmail,
            'event' => 'creating',
        ]);

        $cde->ddp_cde_statut_id = 2;
        $cde->save();

        return redirect()->route('cde.show', $cde->id)->with('success', 'L\'email a été envoyé avec succès');
    }

    public function skipMails($id)
    {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 2;
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }

    public function getRetours($id)
    {
        $cde = Cde::findOrFail($id);
        $data = $cde->cdeLignes->map(function ($ligne) {
            return [
                $ligne->ddpCdeStatut->nom,
                $ligne->quantite,
                $ligne->prix_unitaire,
                $ligne->typeExpedition->short,
                $ligne->date_livraison_reelle ? Carbon::parse($ligne->date_livraison_reelle)->format('d/m/Y') : null,
                $ligne->non_livre,
            ];
        });
        return $data;
    }
    public function saveRetours($id, Request $request)
    {
        $cde = Cde::findOrFail($id);
        if ($cde->affaire && $cde->affaire->statut === \App\Models\Affaire::STATUT_TERMINE) {
            abort(403, 'Impossible de modifier une commande liée à une affaire terminée.');
        }
        $data = array_map('str_getcsv', array_filter(explode("\r\n", $request->data), 'strlen'));
        $compteur = 0;
        $changements = $cde->changement_livraison ? json_decode($cde->changement_livraison, true) : [];
        foreach ($cde->cdeLignes as $ligne) {
            $originalData = [
                'ddp_cde_statut_id' => $ligne->ddp_cde_statut_id,
                'quantite' => $ligne->quantite,
                'prix_unitaire' => $ligne->prix_unitaire,
                'type_expedition_id' => $ligne->type_expedition_id,
                'non_livre' => $ligne->non_livre,
            ];

            $ligne->ddp_cde_statut_id = DdpCdeStatut::where('nom', $data[$compteur][0])->first()->id ?? $ligne->ddp_cde_statut_id;
            $ligne->quantite = $data[$compteur][1];
            $ligne->prix_unitaire = $data[$compteur][2];
            $ligne->type_expedition_id = TypeExpedition::where('short', $data[$compteur][3])->first()->id ?? $ligne->type_expedition_id;
            $ligne->date_livraison_reelle = $data[$compteur][4] ? Carbon::createFromFormat('d/m/Y', $data[$compteur][4]) : null;
            $ligne->non_livre = filter_var($data[$compteur][5], FILTER_VALIDATE_BOOLEAN);

            $newData = [
                'ddp_cde_statut_id' => $ligne->ddp_cde_statut_id,
                'quantite' => $ligne->quantite,
                'prix_unitaire' => $ligne->prix_unitaire,
                'type_expedition_id' => $ligne->type_expedition_id,
                'non_livre' => $ligne->non_livre,
            ];

            foreach ($originalData as $key => $value) {
                if ($value != $newData[$key]) {
                    // Traduction des noms de champs en français
                    $fieldNames = [
                        'ddp_cde_statut_id' => 'Statut',
                        'quantite' => 'Quantité',
                        'prix_unitaire' => 'Prix unitaire',
                        'type_expedition_id' => 'Type d\'expédition',
                        'non_livre' => 'Non livré',
                    ];

                    $fieldName = $fieldNames[$key] ?? $key;

                    // Récupération des valeurs lisibles pour les IDs
                    $oldValue = $value;
                    $newValue = $newData[$key];

                    if ($key === 'ddp_cde_statut_id') {
                        $oldValue = DdpCdeStatut::find($value)->nom ?? $value;
                        $newValue = DdpCdeStatut::find($newData[$key])->nom ?? $newData[$key];
                    } elseif ($key === 'type_expedition_id') {
                        $oldValue = TypeExpedition::find($value)->short ?? $value;
                        $newValue = TypeExpedition::find($newData[$key])->short ?? $newData[$key];
                    } elseif ($key === 'prix_unitaire') {
                        $oldValue = formatNumberArgent($value);
                        $newValue = formatNumberArgent($newData[$key]);
                    } elseif ($key === 'quantite') {
                        $oldValue = formatNumber($value);
                        $newValue = formatNumber($newData[$key]);
                    } elseif ($key === 'non_livre') {
                        $oldValue = $value ? 'Oui' : 'Non';
                        $newValue = $newData[$key] ? 'Oui' : 'Non';
                    }


                    $changements[] = [
                        'ligne_id' => $ligne->id,
                        'description' => "Modification de {$fieldName} : {$oldValue} → {$newValue}",
                        'field' => $key,
                        'old_value' => $value,
                        'new_value' => $newData[$key],
                        'date' => now(),
                    ];
                }
            }
            $cde->changement_livraison = json_encode($changements);
            $cde->save();
            $ligne->save();
            $compteur++;
            $data2[] = $ligne;
        }

        return $data2;
    }

    public function terminer($id)
    {
        $cde = Cde::findOrFail($id);

        // Validation : vérifier que toutes les lignes ont une date de livraison ou sont marquées comme non livrées
        foreach ($cde->cdeLignes as $ligne) {
            if (!$ligne->non_livre && is_null($ligne->date_livraison_reelle) && $ligne->ddp_cde_statut_id != 4) { // Ignorer les lignes annulées
                return redirect()->back()->with('error', 'La date de livraison réelle est obligatoire pour toutes les lignes, sauf si "Non livré" est coché.');
            }
        }

        $cde->ddp_cde_statut_id = 3;
        $total_ht = 0;
        foreach ($cde->cdeLignes as $ligne) {
            if ($ligne->date_livraison_reelle && $ligne->ddp_cde_statut_id != 4) {
                $ligne->prix = $ligne->prix_unitaire * $ligne->quantite;
                $ligne->save();
                $total_ht += $ligne->prix_unitaire * $ligne->quantite;
            }
        }
        // dd($total_ht_table);
        $cde->total_ht = $total_ht + $cde->frais_de_port + $cde->frais_divers;
        $cde->total_ttc = $cde->total_ht * (1 + ($cde->tva / 100));
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }
    public function annulerTerminer($id)
    {
        DB::beginTransaction();
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 2;
        $cde->save();
        foreach ($cde->cdeLignes->whereIn('is_stocke', [true, false]) as $ligne) {
            $went_wrong = false;
            foreach ($ligne->mouvementsStock as $mouvement) {
                try {
                    $this->stockService->deleteStockFromMouvement($mouvement);
                } catch (Exception $e) {
                    Log::error('Erreur lors de la suppression du mouvement de stock ID: ' . $mouvement->id .
                        ' pour la ligne de commande ID: ' . $ligne->id . ' - ' . $e->getMessage());
                    $went_wrong = true;
                    continue;
                }
            }
            if ($went_wrong) {
                Log::error('Erreur lors de la suppression des mouvements de stock pour la ligne de commande ID: ' . $ligne->id);
                continue;
            }
            $ligne->is_stocke = null;
            $ligne->save();
        }
        DB::commit();
        return redirect()->route('cde.show', $cde->id);
    }
    public function terminerControler($id)
    {

        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 5;
        $societe = $cde->societe;
        foreach ($cde->cdeLignes as $ligne) {
            $matiere = $ligne->matiere;
            if ($ligne->date_livraison_reelle && $ligne->ddp_cde_statut_id != 4 && $ligne->ligne_autre_id == null) {
                $societe_matiere = $matiere->societeMatieres()->firstOrCreate(['societe_id' => $societe->id]);
                $newPrix = $ligne->prix_unitaire;
                if ($matiere->getLastPrice($societe->id) == null || $matiere->getLastPrice($societe->id)->prix_unitaire != $newPrix) {
                    SocieteMatierePrix::updateOrCreate(
                        [
                            'societe_matiere_id' => $societe_matiere->id,
                            'cde_ligne_id' => $ligne->id,
                        ],
                        [
                            'prix_unitaire' => $newPrix ?? null,
                            'date' => now(),
                        ]
                    );
                }
            }
        }
        $cde->save();
        if ($cde->affaire) {
            $cde->affaire->updateTotal();
        }
        return redirect()->route('cde.show', [
            'cde' => $cde->id,
        ])->with('success', 'Commande controlée avec succès');
    }
    public function annulerTerminerControler($id)
    {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 3;
        $cde->save();

        return redirect()->route('cde.show', $cde->id);
    }
    public function storeStock(Request $request, $cdeId)
    {
        $cde = Cde::findOrFail($cdeId);
        $stockData = $request->input('stock', []);

        DB::beginTransaction();
        try {
            foreach ($stockData as $poste => $data) {
                $ligne = $cde->cdeLignes()->where('poste', $poste)->firstOrFail();
                $matiere = $ligne->matiere;

                if (isset($data['rows'])) {
                    foreach ($data['rows'] as $row) {
                        $quantity = $row['quantity'] ?? 0;
                        $unitValue = $row['unit_value'] ?? null;

                        if ($quantity > 0) {
                            $this->stockService->stock(
                                $matiere->id,
                                'entree',
                                $quantity,
                                $unitValue,
                                'Livraison - ' . $cde->code,
                                $ligne->id
                            );
                            $ligne->is_stocke = true;
                            $ligne->save();
                        }
                    }
                } elseif (isset($data['entree'])) {
                    $quantity = $data['entree'] ?? 0;

                    if ($quantity > 0) {
                        $this->stockService->stock(
                            $matiere->id,
                            'entree',
                            $quantity,
                            null,
                            'Livraison commande - ' . $cde->code,
                            $ligne->id
                        );
                        $ligne->is_stocke = true;
                        $ligne->save();
                    }
                }
            }

            DB::commit();
            return redirect()->route('cde.show', $cdeId)->with('success', 'Mouvements de stock enregistrés avec succès.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'enregistrement des mouvements de stock', [
                'exception' => $e->getMessage(),
                'cde_id' => $cdeId,
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement des mouvements de stock.');
        }
    }
    public function storeStockLigne(Request $request, $cdeId, $ligneId): JsonResponse
    {
        $cde = Cde::findOrFail($cdeId);
        $ligne = $cde->cdeLignes()->findOrFail($ligneId);
        $matiere = $ligne->matiere;
        $stockData = $request->input('stock', []);

        DB::beginTransaction();
        try {
            if (isset($stockData['rows'])) {
                foreach ($stockData['rows'] as $row) {
                    $quantity = $row['quantity'] ?? 0;
                    $unitValue = $row['unit_value'] ?? null;

                    if ($quantity > 0) {
                        $this->stockService->stock(
                            $matiere->id,
                            'entree',
                            $quantity,
                            $unitValue,
                            'Livraison - ' . $cde->code,
                            $ligne->id
                        );
                        $ligne->is_stocke = true;
                        $ligne->save();
                    }
                }
            } elseif (isset($stockData['entree'])) {
                $quantity = $stockData['entree'] ?? 0;

                if ($quantity > 0) {
                    $this->stockService->stock(
                        $matiere->id,
                        'entree',
                        $quantity,
                        null,
                        'Livraison commande - ' . $cde->code,
                        $ligne->id
                    );
                    $ligne->is_stocke = true;
                    $ligne->save();
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Mouvement de stock enregistré avec succès.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'enregistrement du mouvement de stock', [
                'exception' => $e->getMessage(),
                'cde_id' => $cdeId,
                'ligne_id' => $ligneId,
            ]);
            return response()->json(['success' => false, 'error' => 'Une erreur est survenue lors de l\'enregistrement du mouvement de stock.'], 500);
        }
    }

    public function noStock($id)
    {
        $cde = Cde::findOrFail($id);
        $cde_lignes = $cde->cdeLignes()
            ->with('ddpCdeStatut')
            ->whereHas('ddpCdeStatut', function ($query) {
                $query->where('nom', '!=', 'Annulée');
            })
            ->whereNotNull('date_livraison_reelle')
            ->whereNull('is_stocke')
            ->whereNull('ligne_autre_id')
            ->get();
        if ($cde_lignes->isEmpty()) {
            return redirect()->route('cde.show', $cde->id)->with('error', 'Aucun mouvement de stock à valider');
        }
        foreach ($cde_lignes as $ligne) {
            $ligne->is_stocke = false;
            $ligne->save();
        }
        return redirect()->route('cde.show', $cde->id)->with('success', 'Commande validée sans mouvement de stock');
    }

    /**
     * Retourne le prochain code de CDE
     * @param mixed $entite_id
     * @return string
     */
    public function getLastCode($entite_id)
    {
        $entite = Entite::findOrFail($entite_id);
        $lastcode = CDE::where('code', 'LIKE', 'CDE-' . date('y') . '%')
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
        $cde = Cde::find($id);
        if ($cde) {
            if ($cde->affaire && $cde->affaire->statut === \App\Models\Affaire::STATUT_TERMINE) {
                return response()->json(['error' => 'Impossible de modifier le commentaire d\'une commande liée à une affaire terminée.'], 403);
            }
            // Trouve le commentaire lié à la commande
            $commentaire = $cde->commentaire;
            if ($commentaire) {
                if ($commentaire->contenu == $request->commentaire) {
                    return response()->json(['message' => 'Commentaire inchangé'], 200);
                }
                // Met à jour le commentaire avec la nouvelle valeur
                $commentaire->contenu = $request->commentaire;
                $commentaire->save();
                return response()->json(['message' => 'Commentaire mis à jour avec succès'], 200);
            } else {
                // Si la commande n'a pas encore de commentaire, on en crée un
                $commentaire = new Commentaire();
                $commentaire->contenu = $request->commentaire;
                $cde->commentaire()->save($commentaire);
                return response()->json(['message' => 'Commentaire créé avec succès'], 201);
            }
        }
    }

    public function updateMouvement(Request $request, $mouvementId)
    {
        $request->validate([
            'quantity' => 'nullable|numeric|min:0',
            'unit_value' => 'nullable|numeric|min:0',
        ]);

        try {
            $mouvement = \App\Models\MouvementStock::findOrFail($mouvementId);

            $newQuantity = $request->input('quantity', $mouvement->quantite);
            $newUnitValue = $request->input('unit_value', $mouvement->valeur_unitaire);

            $result = $this->stockService->modifierMouvement($mouvement, $newQuantity, $newUnitValue);

            return response()->json([
                'success' => true,
                'message' => 'Mouvement mis à jour',
                'new_id' => $result['mouvement']->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeMouvement(Request $request, $cdeId, $ligneId)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0',
            'unit_value' => 'required|numeric|min:0',
        ]);

        try {
            $cde = Cde::findOrFail($cdeId);
            $ligne = $cde->cdeLignes()->findOrFail($ligneId);
            $matiere = $ligne->matiere;

            $quantity = $request->input('quantity');
            $unitValue = $request->input('unit_value');

            if ($quantity > 0) {
                $result = $this->stockService->stock(
                    $matiere->id,
                    'entree',
                    $quantity,
                    $unitValue,
                    'Livraison - ' . $cde->code,
                    $ligne->id
                );

                // Retrieve the created movement. stockService->stock returns array with 'mouvement' key if created?
                // Let's check StockService::stock return value.
                // It returns array.

                $mouvement = $result['mouvement'] ?? null;

                if (!$mouvement) {
                     // Fallback if service doesn't return it, though it should.
                     // Actually stockService->stock returns ['mouvement' => $mouvement] in my previous read?
                     // Let's verify.
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Mouvement ajouté',
                    'mouvement_id' => $mouvement ? $mouvement->id : null
                ]);
            }

            return response()->json(['error' => 'Quantité invalide'], 400);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroyMouvement($mouvementId)
    {
        try {
            $mouvement = \App\Models\MouvementStock::findOrFail($mouvementId);
            $this->stockService->deleteStockFromMouvement($mouvement);
            return response()->json(['success' => true, 'message' => 'Mouvement supprimé']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroyMouvements($cdeId, $ligneid)
    {
        try {
            $cde = Cde::findOrFail($cdeId);
            $cdeLigne = $cde->cdeLignes()->findOrFail($ligneid);
            $mouvements = $cdeLigne->mouvementsStock()->get();

            if (!$mouvements) {
                return response()->json(['error' => 'Mouvement introuvable'], 404);
            }


            // Supprimer le mouvement et ajuste le stock
            foreach ($mouvements as $mouvement) {
                $this->stockService->deleteStockFromMouvement($mouvement);
            }
            // mettre à jour le statut is_stocke
            if ($cdeLigne) {
                $cdeLigne->is_stocke = null;
                $cdeLigne->save();
            }
            return response()->json(['success' => 'Mouvement supprimé avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression des mouvements de stock', [
                'exception' => $e->getMessage(),
                'cde_ligne_id' => $ligneid,
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function annuler($id)
    {
        $cde = Cde::findOrFail($id);
        // Save the current status as old_statut, but only if it's not the cancellation status (4)
        if ($cde->ddp_cde_statut_id != 4) {
            $cde->old_statut = $cde->ddp_cde_statut_id;
        }
        $cde->ddp_cde_statut_id = 4;
        $cde->save();

        return redirect()->route('cde.show', $cde->id)->with('success', 'Commande annulée avec succès');
    }
    public function reprendre($id)
    {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = $cde->old_statut;
        $cde->save();

        return redirect()->route('cde.show', $cde->id)->with('success', 'Commande reprise avec succès');
    }
}

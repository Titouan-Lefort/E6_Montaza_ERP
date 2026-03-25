<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatiereResource;
use App\Models\DossierStandard;
use App\Models\Famille;
use App\Models\Material;
use App\Models\Matiere;
use App\Models\ModelChange;
use App\Models\MouvementStock;
use App\Models\Societe;
use App\Models\SocieteMatiere;
use App\Models\SousFamille;
use App\Models\Standard;
use App\Models\StandardVersion;
use App\Models\Stock;
use App\Models\Unite;
use App\Services\StockService;
use App\Models\DevisTuyauterie;
use App\Models\DevisStockReservation;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MatiereController extends Controller
{
    // Dans votre contrôleur

    /**
     * Méthode privée qui construit la requête de recherche principale.
     */
    private function buildMatiereQuery(Request $request, $second_search = false)
    {
        $query = Matiere::with(['sousFamille', 'societe', 'standardVersion']);

        // Filtrer par famille
        if ($request->filled('famille')) {
            $query->whereHas('sousFamille', function ($subQuery) use ($request) {
                $subQuery->where('famille_id', $request->input('famille'));
            });
        }
        // Filtrer par sous-famille
        if ($request->filled('sous_famille')) {
            $query->where('sous_famille_id', $request->input('sous_famille'));
        }

        // Filtrer par établissement (prioritaire) ou société (fournisseur)
        if ($request->filled('etablissement')) {
            $etablissementId = $request->input('etablissement');
            // Vérifier si l'établissement a des matières associées
            $hasMatieres = \DB::table('societe_matieres')
                ->where('etablissement_id', $etablissementId)
                ->exists();

            if ($hasMatieres) {
                // Afficher les matières spécifiques à cet établissement
                $query->whereHas('societeMatieres', function ($subQuery) use ($etablissementId) {
                    $subQuery->where('etablissement_id', $etablissementId);
                });
            } else {
                // Si l'établissement n'a pas encore de matières, afficher celles du fournisseur parent
                $etablissement = \App\Models\Etablissement::find($etablissementId);
                if ($etablissement && $etablissement->societe_id) {
                    $query->whereHas('societeMatieres', function ($subQuery) use ($etablissement) {
                        $subQuery->where('societe_id', $etablissement->societe_id);
                    });
                }
            }
        } elseif ($request->filled('societe')) {
            // Filtrer par société seulement si pas d'établissement spécifié
            $societeId = $request->input('societe');
            // Vérifier si le fournisseur a des matières associées
            $hasMatieres = \DB::table('societe_matieres')
                ->where('societe_id', $societeId)
                ->exists();

            // Appliquer le filtre seulement si le fournisseur a des matières associées
            if ($hasMatieres) {
                $query->whereHas('societeMatieres', function ($subQuery) use ($societeId) {
                    $subQuery->where('societe_id', $societeId);
                });
            }
            // Si le fournisseur n'a pas de matières associées, on n'applique pas de filtre
            // Cela permet d'afficher toutes les matières pour un nouveau fournisseur
        }
        // Filtrer par société (ancien paramètre)
        if ($request->filled('societe_filter')) {
            $query->whereHas('societeMatieres', function ($subQuery) use ($request) {
                $subQuery->where('societe_id', $request->input('societe'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $terms = explode(' ', $search);

            if (count($terms) == 1 && !$second_search) {
                // Première tentative : recherche exacte sur ref_interne et ref_externe
                $query->where(function ($q) use ($search) {
                    $q->where('ref_interne', '=', $search)
                        ->orWhereHas('societeMatieres', function ($subSubQuery) use ($search) {
                            $subSubQuery->where('ref_externe', 'ILIKE', $search);
                        });
                });

                $query_test = clone $query;
                $results = $query_test->get();

                Log::info("Debug recherche exacte", [
                    'search' => $search,
                    'count_results' => $results->count(),
                    'has_famille_filter' => $request->filled('famille'),
                    'has_sous_famille_filter' => $request->filled('sous_famille'),
                    'has_societe_filter' => $request->filled('societe'),
                    'sql' => $query_test->toSql(),
                    'bindings' => $query_test->getBindings()
                ]);

                if ($results->isEmpty()) {
                    Log::info("No results found for single term search: {$search}");
                    // Deuxième tentative : recherche flexible
                    return $this->buildMatiereQuery($request, true);
                }
            } else {
                // Recherche multi-termes OU deuxième tentative pour terme unique
                $query->where(function ($q) use ($terms, $second_search) {
                    $hasValidSearchTerms = false;

                    foreach ($terms as $term) {
                        $q->where(function ($subQuery) use ($term, $terms, &$hasValidSearchTerms, $second_search) {
                            if (stripos($term, 'dn') === 0) {
                                $value = substr($term, 2);
                                $subQuery->where('dn', '=', $value);
                                $hasValidSearchTerms = true;
                            } elseif (stripos($term, 'ep') === 0) {
                                $value = str_replace([',', '.'], ['.', ','], substr($term, 2));
                                $subQuery->where('epaisseur', '=', $value);
                                $hasValidSearchTerms = true;
                            } else {
                                // Seulement si le terme fait au moins 1 caractère
                                if (strlen(trim($term)) >= 1) {
                                    // Vérifier le mode de recherche (depuis la requête)
                                    $searchMode = request()->input('search_mode', 'contains');

                                    if ($searchMode === 'start_with') {
                                        // Recherche au début de la désignation uniquement (pour établissements)
                                        $subQuery->where('designation', 'ILIKE', "{$term}%");
                                    } else {
                                        // Recherche partout (par défaut, pour DDP/CDE)
                                        $subQuery->where('designation', 'ILIKE', "%{$term}%")
                                            ->orWhere('ref_interne', 'ILIKE', "%{$term}%");
                                    }

                                    // Pour une recherche avec un seul terme (première ou deuxième tentative)
                                    if (count($terms) == 1) {
                                        $subQuery->orWhereHas('societeMatieres', function ($subSubQuery) use ($term) {
                                            $subSubQuery->where('ref_externe', 'ILIKE', "%{$term}%");
                                        });
                                    }
                                    $hasValidSearchTerms = true;
                                }
                            }
                        });
                    }

                    // Si aucun terme valide n'a été trouvé, forcer une condition impossible
                    if (!$hasValidSearchTerms) {
                        $q->whereRaw('1 = 0');
                    }
                });

                // Ajouter un score de pertinence pour ordonner les résultats
                $relevanceScore = [];
                foreach ($terms as $index => $term) {
                    if (strlen(trim($term)) >= 1 && stripos($term, 'dn') !== 0 && stripos($term, 'ep') !== 0) {
                        $escapedTerm = str_replace("'", "''", $term); // Échapper les quotes pour SQL
                        // Score pour sous-famille (priorité 3)
                        $relevanceScore[] = "CASE WHEN EXISTS (
                            SELECT 1 FROM sous_familles sf
                            WHERE sf.id = matieres.sous_famille_id
                            AND sf.nom ILIKE '%{$escapedTerm}%'
                        ) THEN 3 ELSE 0 END";

                        // Score pour ref_interne (priorité 2)
                        $relevanceScore[] = "CASE WHEN matieres.ref_interne ILIKE '%{$escapedTerm}%' THEN 2 ELSE 0 END";

                        // Score pour désignation (priorité 1)
                        $relevanceScore[] = "CASE WHEN matieres.designation ILIKE '%{$escapedTerm}%' THEN 1 ELSE 0 END";
                    }
                }

                if (!empty($relevanceScore)) {
                    $scoreExpression = implode(' + ', $relevanceScore);
                    $query->selectRaw("*, ($scoreExpression) as relevance_score");
                    $query->orderBy('relevance_score', 'desc');
                }
            }
        }

        // Add sorting by stock quantity if requested (après le tri par pertinence)
        $query->addSelect(['total_stock' => function ($q) {
            $q->selectRaw('COALESCE(SUM(CASE WHEN stocks.valeur_unitaire > 0 THEN stocks.quantite * stocks.valeur_unitaire ELSE stocks.quantite END), 0)')
                ->from('stocks')
                ->whereColumn('stocks.matiere_id', 'matieres.id');
        }]);

        // Si pas de tri par pertinence, trier par stock
        if (!$request->filled('search') || (count(explode(' ', $request->input('search'))) == 1 && !$second_search)) {
            $query->orderBy('total_stock', 'desc');
        } else {
            // Trier d'abord par pertinence, puis par stock en cas d'égalité
            $query->orderBy('total_stock', 'desc');
        }

        return $query;
    }
    /**
     * Méthode searchResult avec pagination.
     */
    public function searchResult(Request $request, $wantJson = true)
    {
        // Validation des données d'entrée
        $request->validate([
            'search'        => 'nullable|string|max:255',
            'nombre'        => 'nullable|integer|min:1|max:10000',
            'famille'       => 'nullable|integer|exists:familles,id',
            'sous_famille'  => 'nullable|integer|exists:sous_familles,id',
            'page'          => 'nullable|integer|min:1',
            'societe_filter' => 'nullable|integer|exists:societes,id',
        ]);

        $nombre = intval($request->input('nombre', 50));

        // Construction de la requête avec la logique principale
        $query = $this->buildMatiereQuery($request);
        $query->orderBy('sous_famille_id');

        // Récupérer les résultats paginés
        $matieres = $query->paginate($nombre);

        if ($wantJson) {
            $links    = $matieres->appends($request->query())->links()->toHtml();
            $links    = str_replace('/search', '', $links);
            $lastPage = $matieres->lastPage();

            return response()->json([
                'matieres' => MatiereResource::collection($matieres),
                'links'    => $links,
                'lastPage' => $lastPage,
            ]);
        }

        return $matieres;
    }

    /**
     * Méthode quickSearch avec limite de 50 résultats.
     */
    public function quickSearch(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'search'         => 'nullable|string|max:255',
            'famille'        => 'nullable|integer|exists:familles,id',
            'sous_famille'   => 'nullable|integer|exists:sous_familles,id',
            'with_last_price' => 'nullable|boolean',
            'societe'        => 'nullable|integer|exists:societes,id',
            'etablissement'  => 'nullable|integer|exists:etablissements,id',
            'search_mode'    => 'nullable|string|in:contains,start_with',
        ]);

        // Construction de la requête avec la logique principale
        $query = $this->buildMatiereQuery($request);
        $query->orderBy('sous_famille_id');

        // Appliquer la limite APRÈS tous les tris
        $matieres = $query->limit(50)->get();

        return response()->json([
            'matieres' => MatiereResource::collection($matieres),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $familles = Famille::all();
        $societes = Societe::fournisseurs()
            ->withCount('matieres')
            ->orderByDesc('matieres_count')
            ->get()
            ->map(function ($societe) {
                $societe->raison_sociale .= ' (' . $societe->matieres_count . ')';
                return $societe;
            });
        return view('matieres.index', [
            'familles' => $familles,
            'societes' => $societes,
        ]);
    }

    /**
     * Vérification des matières disponibles pour les devis actifs
     */
    public function devisVerification(): View
    {
        $devis = DevisTuyauterie::with(['sections.lignes', 'affaire', 'societe', 'stockReservations'])
            ->where('is_archived', false)
            ->orderBy('date_emission', 'desc')
            ->get();

        return view('matieres.devis_verification', compact('devis'));
    }

    /**
     * Assigner/Réserver du stock pour un devis
     */
    public function assignerStockDevis(Request $request)
    {
        $request->validate([
            'devis_id' => 'required|exists:devis_tuyauteries,id',
            'matiere_id' => 'required|exists:matieres,id',
            'quantite' => 'required|numeric|min:0.01',
        ]);

        $matiere = Matiere::findOrFail($request->matiere_id);
        $stockDisponible = $matiere->quantite();

        // Vérifier qu'il y a assez de stock
        if ($stockDisponible < $request->quantite) {
            return redirect()->back()->with('error', 'Stock insuffisant pour cette réservation.');
        }

        // Créer la réservation
        DevisStockReservation::create([
            'devis_tuyauterie_id' => $request->devis_id,
            'matiere_id' => $request->matiere_id,
            'quantite_reservee' => $request->quantite,
            'user_id' => Auth::id(),
            'statut' => 'reserve',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Stock réservé avec succès pour ce devis.');
    }

    public function sousFamillesJson(Famille $famille)
    {
        $sousFamilles = $famille->sousFamilles->map(function ($sousFamille) {
            $sousFamille->matiere_count = $sousFamille->matieres()->count();
            return $sousFamille;
        });

        return response()->json($sousFamilles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ref_interne' => 'required|string|unique:matieres,ref_interne',
            'standard_id' => 'nullable|exists:standards,id',
            'designation' => 'required|string|max:255',
            'societe_id' => 'required|exists:societes,id',
            'unite_id' => 'required|exists:unites,id',
            'sous_famille_id' => 'required|exists:sous_familles,id',
            'dn' => 'nullable|integer',
            'epaisseur' => 'nullable|numeric',
            'quantite' => 'required|integer',
            'stock_min' => 'nullable|integer',
        ]);

        $matiere = Matiere::create($request->all());

        return response()->json(new MatiereResource($matiere), 201);
    }
    public function fournisseursJson(Matiere $matiere)
    {
        return response()->json($matiere->fournisseurs);
    }

    /**
     * Retourne les informations d'une matière en JSON pour la présélection dans les commandes
     */
    public function getMatiereJson($id)
    {
        try {
            $matiere = Matiere::with(['sousFamille', 'material', 'unite', 'standardVersion', 'fournisseurs'])
                ->findOrFail($id);

            // Récupérer le dernier prix (objet SocieteMatierePrix ou null)
            $lastPriceObj = $matiere->getLastPrice();
            $lastPrice = $lastPriceObj ? $lastPriceObj->prix : null;
            $lastPriceDate = $lastPriceObj ? $lastPriceObj->date : null;

            // Récupérer les fournisseurs de cette matière
            $fournisseurs = $matiere->fournisseurs->map(function($fournisseur) {
                return [
                    'id' => $fournisseur->id,
                    'raison_sociale' => $fournisseur->raison_sociale ?? $fournisseur->nom,
                ];
            });

            return response()->json([
                'id' => $matiere->id,
                'refInterne' => $matiere->ref_interne ?? '',
                'refexterne' => $matiere->ref_externe ?? '',
                'designation' => $matiere->designation ?? '',
                'refValeurUnitaire' => $matiere->ref_valeur_unitaire ?? 1,
                'typeAffichageStock' => $matiere->typeAffichageStock(),
                'lastPrice' => $lastPrice,
                'lastPriceUnite' => $matiere->unite ? $matiere->unite->short : '',
                'Unite' => $matiere->unite ? $matiere->unite->short : '',
                'lastPriceDate' => $lastPriceDate ? \Carbon\Carbon::parse($lastPriceDate)->format('d/m/Y') : null,
                'lastPrice_formated' => $lastPrice ? number_format($lastPrice, 2, ',', ' ') . ' €' : '0 €',
                'material' => $matiere->material ? $matiere->material->nom : '',
                'sousFamille' => $matiere->sousFamille ? $matiere->sousFamille->nom : '',
                'dn' => $matiere->dn ?? '',
                'epaisseur' => $matiere->epaisseur ?? '',
                'fournisseurs' => $fournisseurs,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur getMatiereJson pour matiere_id ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération de la matière',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($matiere_id, Request $request): View
    {
        // Validation des filtres de prix
        $request->validate([
            'periode_prix' => 'nullable|in:today,week,month,3months,6months,year,custom',
            'date_debut_prix' => 'nullable|date',
            'date_fin_prix' => 'nullable|date|after_or_equal:date_debut_prix',
        ]);

        $matiere = Matiere::with(['sousFamille', 'societe', 'standardVersion'])->findOrFail($matiere_id);
        $fournisseurs = $matiere->fournisseurs()
            ->get();
        foreach ($fournisseurs as $fournisseur) {
            $fournisseur->prix = $matiere->getLastPrice($fournisseur->id);
            $fournisseur->ref_externe = $matiere->societeMatiere($fournisseur->id)->ref_externe;
        }

        // Get historical movement data for charts
        $mouvements = $matiere->mouvementStocks()->orderBy('date', 'asc')->get();
        $dates = $mouvements->pluck('date');

        // Initialize tracking variables
        $stockHistory = [];
        $currentStock = [];

        // For type 2 materials (tracked by unit value)
        if ($matiere->typeAffichageStock() == 2) {
            // Group stocks by valeur_unitaire
            $stocksByValue = $matiere->stock->groupBy('valeur_unitaire');

            // Initialize current stock for each value
            foreach ($stocksByValue as $valeur => $stocks) {
                $currentStock[$valeur] = 0;
            }

            // Calculate running total for each movement
            foreach ($mouvements as $mouvement) {
                $valeur = $mouvement->valeur_unitaire;

                // Initialize if this unit value wasn't seen before
                if (!isset($currentStock[$valeur])) {
                    $currentStock[$valeur] = 0;
                }

                // Update the stock based on movement type
                if ($mouvement->type == 'entree') {
                    $currentStock[$valeur] += $mouvement->quantite;
                } else {
                    $currentStock[$valeur] -= $mouvement->quantite;
                }

                // Store the point in time snapshot
                $stockHistory[] = [
                    'date' => $mouvement->date,
                    'valeur_unitaire' => $valeur,
                    'quantite' => $currentStock[$valeur],
                    'total' => array_sum($currentStock)
                ];
            }

            // Get total quantity for chart
            $quantites = collect($stockHistory)->pluck('total');
        }
        // For type 1 materials (simple quantity tracking)
        else {
            $currentQuantity = 0;

            // Calculate running total for each movement
            foreach ($mouvements as $mouvement) {
                if ($mouvement->type == 'entree') {
                    $currentQuantity += $mouvement->quantite;
                } else {
                    $currentQuantity -= $mouvement->quantite;
                }

                $stockHistory[] = [
                    'date' => $mouvement->date,
                    'quantite' => $currentQuantity
                ];
            }

            // Get total quantity for chart
            $quantites = collect($stockHistory)->pluck('quantite');
        }

        // Récupération des données de prix pour tous les fournisseurs
        $prixParFournisseur = [];
        $datesPrix = collect();
        $hasPriceData = false;

        // Construire la requête de prix avec filtrage par période
        foreach ($fournisseurs as $fournisseur) {
            $queryPrix = $matiere->prixPourSociete($fournisseur->id);

            // Appliquer les filtres de période pour les prix
            if ($request->filled('periode_prix')) {
                $periode = $request->input('periode_prix');

                switch ($periode) {
                    case 'today':
                        $queryPrix->whereDate('date', today());
                        break;
                    case 'week':
                        $queryPrix->where('date', '>=', now()->startOfWeek());
                        break;
                    case 'month':
                        $queryPrix->where('date', '>=', now()->startOfMonth());
                        break;
                    case '3months':
                        $queryPrix->where('date', '>=', now()->subMonths(3));
                        break;
                    case '6months':
                        $queryPrix->where('date', '>=', now()->subMonths(6));
                        break;
                    case 'year':
                        $queryPrix->where('date', '>=', now()->startOfYear());
                        break;
                    case 'custom':
                        if ($request->filled('date_debut_prix')) {
                            $queryPrix->whereDate('date', '>=', $request->input('date_debut_prix'));
                        }
                        if ($request->filled('date_fin_prix')) {
                            $queryPrix->whereDate('date', '<=', $request->input('date_fin_prix'));
                        }
                        break;
                }
            }

            $prixFournisseur = $queryPrix->orderBy('date', 'asc')->get();

            if ($prixFournisseur->count() > 0) {
                $hasPriceData = true;
                $prixParFournisseur[$fournisseur->id] = [
                    'nom' => $fournisseur->raison_sociale,
                    'couleur' => $this->generateColor($fournisseur->id),
                    'dates' => $prixFournisseur->pluck('date'),
                    'prix' => $prixFournisseur->pluck('prix_unitaire')
                ];

                // Ajouter toutes les dates à la collection globale
                $datesPrix = $datesPrix->merge($prixFournisseur->pluck('date'));
            }
        }

        // Supprimer les doublons et trier les dates
        $datesPrix = $datesPrix->unique()->sort()->values();

        // Convert to collections for the view
        $stockHistory = collect($stockHistory);
        $mouvements = $matiere->mouvementStocks->sortByDesc('created_at');
        // Récupérer les fournisseurs sauf ceux déjà attachés à la matière
        $societes = Societe::fournisseurs()
            ->whereNotIn('id', $matiere->fournisseurs->pluck('id'))->get();

        return view('matieres.show', [
            'matiere' => $matiere,
            'fournisseurs' => $fournisseurs,
            'dates' => $dates,
            'mouvements' => $mouvements,
            'quantites' => $quantites,
            'societes' => $societes,
            'prixParFournisseur' => $prixParFournisseur,
            'datesPrix' => $datesPrix,
            'hasPriceData' => $hasPriceData,
        ]);
    }
    public function generateColor($id)
    {
        // Générer une couleur unique basée sur l'ID
        $hue = ($id * 137) % 360;
        return "hsl({$hue}, 70%, 50%)"; // Saturation et luminosité ajustées pour une bonne visibilité
    }
    public function retirerMatiere($matiere_id, Request $request)
    {
        // Validate the request
        $request->validate([
            'quantite' => 'required|numeric|min:0.01',
            'valeur_unitaire' => 'nullable|numeric|min:0',
            'motif' => 'required|string|max:50',
        ]);

        try {
            // Get the matiere
            $matiere = Matiere::findOrFail($matiere_id);

            // Initialize StockService
            $stockService = new StockService();

            // Process stock exit
            $result = $stockService->stock(
                $matiere_id,
                'sortie',
                $request->quantite,
                $request->valeur_unitaire,
                $request->motif,
                null
            );

            // Check if the result is an error response
            if (is_a($result, \Illuminate\Http\JsonResponse::class)) {
                return redirect()
                    ->back()
                    ->withInput()  // Ajout de cette ligne pour préserver les données du formulaire
                    ->with('error', $result->getData()->error);
            }

            // Success
            return redirect()
                ->route('matieres.show', $matiere_id)
                ->with('success', 'Matière retirée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors du retrait de matière', [
                'matiere_id' => $matiere_id,
                'quantite' => $request->quantite,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()  // Ajout de cette ligne pour préserver les données du formulaire
                ->with('error', 'Une erreur est survenue lors du retrait.');
        }
    }
    public function ajouterMatiere($matiere_id, Request $request)
    {
        // Validate the request
        $request->validate([
            'quantite' => 'required|numeric|min:0.01',
            'valeur_unitaire' => 'nullable|numeric|min:0',
            'motif' => 'required|string|max:50',
        ]);

        try {
            // Get the matiere
            $matiere = Matiere::findOrFail($matiere_id);

            // Initialize StockService
            $stockService = new StockService();

            // Process stock entry
            $result = $stockService->stock(
                $matiere_id,
                'entree',
                $request->quantite,
                $request->valeur_unitaire,
                $request->motif,
                null
            );

            // Check if the result is an error response
            if (is_a($result, \Illuminate\Http\JsonResponse::class)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', $result->getData()->error);
            }

            // Success
            return redirect()
                ->route('matieres.show', $matiere_id)
                ->with('success', 'Matière ajoutée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout de matière', [
                'matiere_id' => $matiere_id,
                'quantite' => $request->quantite,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout.');
        }
    }

    /**
     * Ajuster la valeur unitaire d'une portion de stock existante
     */
    public function ajusterMatiere($matiere_id, Request $request)
    {
        // Validate the request
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantite_ajuster' => 'required|numeric|min:0.01',
            'nouvelle_valeur' => 'required|numeric|min:0',
            'motif' => 'required|string|max:50',
        ]);

        try {
            // Get the matiere and verify ownership
            $matiere = Matiere::findOrFail($matiere_id);
            $stock = Stock::findOrFail($request->stock_id);

            // Make sure the stock belongs to this matiere
            if ($stock->matiere_id != $matiere_id) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Cette entrée de stock n\'appartient pas à cette matière.');
            }

            // Verify the quantity to adjust
            if ($request->quantite_ajuster > $stock->quantite) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'La quantité à ajuster ne peut pas dépasser la quantité disponible.');
            }

            // Initialize StockService
            $stockService = new StockService();

            // Process stock adjustment
            $result = $stockService->ajusterStock(
                $request->stock_id,
                $request->quantite_ajuster,
                $request->nouvelle_valeur,
                $request->motif
            );

            // Check if the result is an error response
            if (is_a($result, \Illuminate\Http\JsonResponse::class)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', $result->getData()->error);
            }

            // Success
            return redirect()
                ->route('matieres.show', $matiere_id)
                ->with('success', 'Valeur unitaire ajustée avec succès pour ' . $request->quantite_ajuster . ' unités');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajustement de la valeur unitaire', [
                'matiere_id' => $matiere_id,
                'stock_id' => $request->stock_id,
                'quantite_ajuster' => $request->quantite_ajuster,
                'nouvelle_valeur' => $request->nouvelle_valeur,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajustement.');
        }
    }

    public function quickCreate($modal_id): View
    {
        $familles = Famille::all();
        $dossier_standards = DossierStandard::all();
        $unites = Unite::all();
        $last_ref = Matiere::max('id') + 1;
        $last_ref = 'AA-' . str_pad($last_ref, 5, '0', STR_PAD_LEFT);
        $societes = Societe::fournisseurs()->get();
        $materiaux = Material::all();
        return view('matieres.quick_create', [
            'familles' => $familles,
            'unites' => $unites,
            'modal_id' => $modal_id,
            'dossier_standards' => $dossier_standards,
            'last_ref' => $last_ref,
            'societes' => $societes,
            'materiaux' => $materiaux,
        ]);
    }
    public function quickStore(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $validated = $request->validate([
                    'standard_id' => 'nullable|exists:standards,nom',
                    'standard_version_id' => 'nullable|exists:standard_versions,version',
                    'ref_interne' => 'required|string|unique:matieres,ref_interne',
                    'designation' => 'required|string|max:255',
                    'unite_id' => 'required|exists:unites,id',
                    'sous_famille_id' => 'required|exists:sous_familles,id',
                    'dn' => 'nullable|string|max:50',
                    'epaisseur' => 'nullable|string|max:50',
                    'stock_min' => 'required|integer',
                    'ref_valeur_unitaire' => 'nullable',
                    'societe_id' => 'nullable|exists:societes,id',
                    'ref_externe' => 'nullable|string|max:255',
                    'material_id' => 'nullable',
                    'force_create' => 'nullable|boolean', // Nouveau champ pour forcer la création
                ]);
                Log::info('Validation passée dans quickStore', ['data' => $validated]);
            } catch (ValidationException $e) {
                Log::warning('Validation échouée dans quickStore', ['errors' => $e->errors()]);
                throw $e;
            }
        } else {
            return response()->json(['error' => 'Méthode non autorisée'], 405);
        }

        $ref_interne = $request->input('ref_interne');
        $ref_externe = $request->input('ref_externe');
        $force_create = $request->input('force_create', false);

        // Vérification des doublons uniquement si on ne force pas la création
        if (!$force_create) {
            $doublons = [];

            // Vérifier si ref_interne existe en tant que ref_externe
            if ($ref_interne) {
                $doublon_ref_interne = SocieteMatiere::where('ref_externe', $ref_interne)
                    ->with(['matiere', 'societe'])
                    ->first();

                if ($doublon_ref_interne) {
                    $doublons[] = [
                        'type' => 'ref_interne_existe_comme_ref_externe',
                        'message' => "La référence interne '{$ref_interne}' est déjà utilisée comme référence externe pour la matière '{$doublon_ref_interne->matiere->designation}' chez {$doublon_ref_interne->societe->raison_sociale}",
                        'matiere_id' => $doublon_ref_interne->matiere->id,
                        'societe' => $doublon_ref_interne->societe->raison_sociale
                    ];
                }
            }

            // Vérifier si ref_externe existe en tant que ref_interne
            if ($ref_externe) {
                $doublon_ref_externe = Matiere::where('ref_interne', $ref_externe)->first();

                if ($doublon_ref_externe) {
                    $doublons[] = [
                        'type' => 'ref_externe_existe_comme_ref_interne',
                        'message' => "La référence externe '{$ref_externe}' est déjà utilisée comme référence interne pour la matière '{$doublon_ref_externe->designation}'",
                        'matiere_id' => $doublon_ref_externe->id
                    ];
                }
            }

            // Si des doublons sont détectés, retourner une alerte
            if (!empty($doublons)) {
                return response()->json([
                    'doublon_detected' => true,
                    'doublons' => $doublons,
                    'message' => 'Des références similaires ont été détectées. Voulez-vous continuer ?'
                ], 409); // 409 Conflict
            }
        }

        $lastref = Matiere::max('id') + 1;
        $dn = $request->input('dn') ?: null;
        $epaisseur = $request->input('epaisseur') ?: null;

        if ($request->input('standard_version_id')) {
            if ($request->input('standard_version_id') === '' || $request->input('standard_id') === '') {
                $standard_version_id = null;
            } else {
                $standard_id = Standard::where('nom', 'ILIKE', $request->input('standard_id'))->first()->id;
                if ($standard_id === null) {
                    return response()->json(['error' => 'Le standard n\'existe pas'], 422);
                }
                $standard_version_id = StandardVersion::where('version', 'ILIKE', $request->input('standard_version_id'))
                    ->where('standard_id', $standard_id)
                    ->first()->id;
                if ($standard_version_id === null) {
                    return response()->json(['error' => 'La version du standard n\'existe pas'], 422);
                }
            }
        } else {
            $standard_version_id = null;
        }

        if ($request->input('ref_valeur_unitaire') === '' || $request->input('ref_valeur_unitaire') === 'non') {
            $ref_valeur_unitaire = null;
        } else {
            $ref_valeur_unitaire = $request->input('ref_valeur_unitaire');
        }
        if ($request->input('material_id') == 0 || $request->input('material_id') === '') {
            $request->merge(['material_id' => null]);
        } else {
            // Vérifier que le material existe bien
            $material = Material::find($request->input('material_id'));
            if (!$material) {
                return response()->json(['error' => 'Le matériau sélectionné n\'existe pas'], 422);
            }
            $request->merge(['material_id' => $request->input('material_id')]);
        }
        $matiere = Matiere::create(
            [
                'ref_interne' => $request->input('ref_interne') ?: 'AA-' . str_pad($lastref, 5, '0', STR_PAD_LEFT),
                'designation' => $request->input('designation'),
                'material_id' => $request->input('material_id') ?? null,
                'unite_id' => $request->input('unite_id'),
                'sous_famille_id' => $request->input('sous_famille_id'),
                'standard_version_id' => $standard_version_id,
                'dn' => $dn,
                'epaisseur' => $epaisseur,
                'prix_moyen' => null,
                'date_dernier_achat' => null,
                'quantite' => 0,
                'stock_min' => $request->input('stock_min'),
                'ref_valeur_unitaire' => $ref_valeur_unitaire,
            ]
        );

        if ($request->input('societe_id') === '' || $request->input('societe_id') === null) {
            $societe_id = null;
        } else {
            $societe_id = $request->input('societe_id');
        }
        if ($request->input('ref_externe') === '' || $request->input('ref_externe') === null) {
            $ref_externe = null;
        } else {
            $ref_externe = $request->input('ref_externe');
        }
        if ($societe_id && $ref_externe !== null) {
            $societe = Societe::findOrFail($societe_id);
            SocieteMatiere::updateOrCreate(
                [
                    'societe_id' => $societe->id,
                    'matiere_id' => $matiere->id,
                ],
                [
                    'ref_externe' => $ref_externe ?? null,
                ]
            );
        }
        // Si la matière a été créée malgré des doublons détectés, plus besoin d'envoyer une notification
        // Suppression de cette partie

        return response()->json([
            'success' => true,
            'matiere' => $matiere,
        ], 201);
    }
    public function storeFamille(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:familles,nom',
        ]);

        $famille = Famille::create($request->only('nom'));

        return response()->json([
            'success' => true,
            'famille' => $famille,
            'message' => 'Famille créée avec succès',
        ], 201);
    }

    public function storeSousFamille(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:sous_familles,nom',
            'famille_id' => 'required|exists:familles,id',
        ]);

        $sousFamille = SousFamille::create($request->all());

        return response()->json([
            'success' => true,
            'sousFamille' => $sousFamille,
        ], 201);
    }

    /**
     * Affiche le formulaire d'édition d'une matière
     */
    public function edit(Matiere $matiere)
    {
        // Récupérer les données nécessaires pour le formulaire
        $familles = Famille::all();
        $sousFamilles = SousFamille::where('famille_id', $matiere->sousFamille->famille_id)->get();
        $unites = Unite::all();
        $dossier_standards = DossierStandard::all();
        $standards = [];
        $versions = [];

        if ($matiere->standardVersion) {
            $standards = Standard::where('dossier_standard_id', $matiere->standardVersion->standard->dossier_standard_id)->get();
            $versions = StandardVersion::where('standard_id', $matiere->standardVersion->standard_id)->get();
        }

        $materials = Material::all();

        // Charger les relations nécessaires pour éviter les erreurs dans la vue
        $matiere->load(['standardVersion.standard.dossierStandard']);

        return view('matieres.edit', compact(
            'matiere',
            'familles',
            'sousFamilles',
            'unites',
            'dossier_standards',
            'standards',
            'versions',
            'materials'
        ));
    }

    /**
     * Met à jour les données d'une matière
     */
    public function update(Request $request, Matiere $matiere)
    {
        // Validation des données
        if ($matiere->isLocked()) {
            // Si la matière est verrouillée, seuls certains champs sont modifiables
            $validated = $request->validate([
                'sous_famille_id' => 'required|exists:sous_familles,id',
                'ref_valeur_unitaire' => 'nullable',
                'standard_id' => 'nullable|exists:standards,nom',
                'standard_version' => 'nullable|exists:standard_versions,version',
                'stock_min' => 'required|numeric|min:0',

            ]);
        } else {
            // Sinon, tous les champs sont modifiables
            $validated = $request->validate([
                'ref_interne' => 'required|string|max:255',
                'designation' => 'required|string|max:255',
                'sous_famille_id' => 'required|exists:sous_familles,id',
                'unite_id' => 'required|exists:unites,id',
                'ref_valeur_unitaire' => 'nullable',
                'dn' => 'nullable|string|max:255',
                'epaisseur' => 'nullable|string|max:255',
                'standard_id' => 'nullable|exists:standards,nom',
                'standard_version' => 'nullable|exists:standard_versions,version',
                'stock_min' => 'required|numeric|min:0',
                'material_id' => 'nullable|exists:materials,id',
            ]);
        }
        if ($request->input('standard_version')) {
            if ($request->input('standard_version') === '' || $request->input('standard_id') === '') {
                $standard_version_id = null;
            } else {
                $standard_id = Standard::where('nom', 'ILIKE', $request->input('standard_id'))->first()->id;
                if ($standard_id === null) {
                    return response()->json(['error' => 'Le standard n\'existe pas'], 422);
                }
                $standard_version_id = StandardVersion::where('version', 'ILIKE', $request->input('standard_version_id'))
                    ->where('standard_id', $standard_id)
                    ->first()->id;
                if ($standard_version_id === null) {
                    return response()->json(['error' => 'La version du standard n\'existe pas'], 422);
                }
            }
        } else {
            $standard_version_id = null;
        }
        $validated['standard_version_id'] = $standard_version_id;
        // Traiter correctement le champ ref_valeur_unitaire (comme dans quickStore)
        if ($request->input('ref_valeur_unitaire') === '' || $request->input('ref_valeur_unitaire') === 'non') {
            $validated['ref_valeur_unitaire'] = null;
        }

        // Si la matière est verrouillée, limiter les champs modifiables
        if ($matiere->isLocked()) {
            $allowedFields = Matiere::EDITABLE;
            $updateData = array_intersect_key($validated, array_flip($allowedFields));
        } else {
            $updateData = $validated;
        }

        $matiere->update($updateData);

        return redirect()->route('matieres.show', $matiere->id)
            ->with('success', 'Matière mise à jour avec succès');
    }

    public function mouvements($id, Request $request)
    {
        $matiere = Matiere::with('mouvementStocks')->findOrFail($id);
        // Validation des filtres
        $request->validate([
            'periode' => 'nullable|in:today,week,month,3months,6months,year,custom',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|in:entree,sortie',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        // Construction de la requête avec filtres
        $query = $matiere->mouvementStocks();

        // Filtre par période
        if ($request->filled('periode')) {
            $periode = $request->input('periode');

            switch ($periode) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->startOfWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->startOfMonth());
                    break;
                case '3months':
                    $query->where('created_at', '>=', now()->subMonths(3));
                    break;
                case '6months':
                    $query->where('created_at', '>=', now()->subMonths(6));
                    break;
                case 'year':
                    $query->where('created_at', '>=', now()->startOfYear());
                    break;
                case 'custom':
                    if ($request->filled('date_debut')) {
                        $query->whereDate('created_at', '>=', $request->input('date_debut'));
                    }
                    if ($request->filled('date_fin')) {
                        $query->whereDate('created_at', '<=', $request->input('date_fin'));
                    }
                    break;
            }
        }

        // Filtre par utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Récupérer les mouvements paginés
        $mouvements = $query->orderBy('created_at', 'desc')->paginate(20);

        // Récupérer la liste des utilisateurs qui ont fait des mouvements sur cette matière
        $utilisateurs = \App\Models\User::whereIn('id', function ($query) use ($matiere) {
            $query->select('user_id')
                ->from('mouvement_stocks')
                ->where('matiere_id', $matiere->id)
                ->distinct();
        })->orderBy('first_name')->orderBy('last_name')->get();

        // Conserver les paramètres de filtrage dans la pagination
        $mouvements->appends($request->query());

        return view('matieres.mouvements', compact('matiere', 'mouvements', 'utilisateurs'));
    }

    public function storeFournisseur(Request $request, $matiere_id)
    {
        // Validation des données
        $request->validate([
            'societe_id' => 'required|exists:societes,id',
            'ref_externe' => 'nullable|string|max:255',
        ]);

        try {
            // Vérifier que la matière existe
            $matiere = Matiere::findOrFail($matiere_id);

            // Vérifier que la société est bien un fournisseur
            $societe = Societe::whereIn('societe_type_id', ['2', '3'])->findOrFail($request->societe_id);

            // Vérifier si la relation n'existe pas déjà
            $existingRelation = SocieteMatiere::where('societe_id', $request->societe_id)
                ->where('matiere_id', $matiere_id)
                ->first();

            if ($existingRelation) {
                return redirect()
                    ->back()
                    ->with('error', 'Ce fournisseur est déjà associé à cette matière.');
            }

            // Créer la relation société-matière
            SocieteMatiere::create([
                'societe_id' => $request->societe_id,
                'matiere_id' => $matiere_id,
                'ref_externe' => $request->ref_externe,
            ]);

            return redirect()
                ->route('matieres.show', $matiere_id)
                ->with('success', 'Fournisseur ajouté avec succès à la matière.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du fournisseur', [
                'matiere_id' => $matiere_id,
                'societe_id' => $request->societe_id,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout du fournisseur.');
        }
    }

    /**
     * Détacher un fournisseur d'une matière et supprimer tous les prix associés
     */
    public function detacherFournisseur($matiere_id, $societe_id)
    {
        try {
            // Vérifier que la matière existe
            $matiere = Matiere::findOrFail($matiere_id);

            // Vérifier que la société est bien un fournisseur
            $societe = Societe::whereIn('societe_type_id', ['2', '3'])->findOrFail($societe_id);

            // Trouver la relation société-matière
            $societeMatiere = SocieteMatiere::where('societe_id', $societe_id)
                ->where('matiere_id', $matiere_id)
                ->first();

            if (!$societeMatiere) {
                return redirect()
                    ->back()
                    ->with('error', 'Ce fournisseur n\'est pas associé à cette matière.');
            }

            // Supprimer tous les prix associés
            \App\Models\SocieteMatierePrix::where('societe_matiere_id', $societeMatiere->id)->delete();

            // Supprimer la relation société-matière
            $societeMatiere->delete();
            ModelChange::create([
                'user_id' => Auth::id(),
                'model_type' => 'SocieteMatiere',
                'before' => $societeMatiere->getOriginal(),
                'after' => $societeMatiere->getAttributes(),
                'event' => 'deleting',
            ]);
            return redirect()
                ->route('matieres.show', $matiere_id)
                ->with('success', "Le fournisseur \"{$societe->raison_sociale}\" a été détaché de la matière avec succès. Tous les prix associés ont été supprimés.");
        } catch (\Exception $e) {
            Log::error('Erreur lors du détachement du fournisseur', [
                'matiere_id' => $matiere_id,
                'societe_id' => $societe_id,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors du détachement du fournisseur.');
        }
    }

    /**
     * Supprimer une matière
     */
    public function destroy(Matiere $matiere)
    {
        try {
            $designation = $matiere->designation;

            // Supprimer la matière (la logique de vérification est dans le modèle)
            $matiere->delete();

            return redirect()
                ->route('matieres.index')
                ->with('success', "La matière \"{$designation}\" a été supprimée avec succès.");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la matière', [
                'matiere_id' => $matiere->id,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Supprimer un mouvement de stock
     */
    public function supprimerMouvement($matiere_id, $mouvement_id)
    {
        // Validate the request
        $request->validate([
            'quantite' => 'required|numeric|min:0.01',
            'valeur_unitaire' => 'nullable|numeric|min:0',
            'motif' => 'required|string|max:50',
        ]);

        try {
            // Get the matiere
            $matiere = Matiere::findOrFail($matiere_id);

            // Initialize StockService
            $stockService = new StockService();

            // Process stock exit
            $result = $stockService->stock(
                $matiere_id,
                'sortie',
                $request->quantite,
                $request->valeur_unitaire,
                $request->motif,
                null
            );

            // Check if the result is an error response
            if (is_a($result, \Illuminate\Http\JsonResponse::class)) {
                return redirect()
                    ->back()
                    ->withInput()  // Ajout de cette ligne pour préserver les données du formulaire
                    ->with('error', $result->getData()->error);
            }

            // Success
            return redirect()
                ->route('matieres.show', $matiere_id)
                ->with('success', 'Matière retirée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors du retrait de matière', [
                'matiere_id' => $matiere_id,
                'quantite' => $request->quantite,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()  // Ajout de cette ligne pour préserver les données du formulaire
                ->with('error', 'Une erreur est survenue lors du retrait.');
        }
    }
    public function modifierMouvement(Request $request, $matiere_id, $mouvement_id)
    {
        $request->validate([
            'quantite' => 'required|numeric|min:0.01',
            'valeur_unitaire' => 'nullable|numeric|min:0',
            'raison' => 'required|string|max:255',
        ]);

        try {
            $matiere = Matiere::findOrFail($matiere_id);
            $mouvement = MouvementStock::where('matiere_id', $matiere_id)->findOrFail($mouvement_id);

            // Vérifier si le mouvement peut être modifié (pas lié à une commande)
            if ($mouvement->cde_ligne_id) {
                return redirect()
                    ->back()
                    ->with('error', 'Impossible de modifier un mouvement lié à une commande.');
            }

            $stockService = new StockService();
            $stockService->modifierMouvement(
                $mouvement,
                $request->quantite,
                $request->valeur_unitaire,
                $request->raison
            );

            return redirect()
                ->route('matieres.mouvements', $matiere_id)
                ->with('success', 'Mouvement de stock modifié avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la modification du mouvement', [
                'matiere_id' => $matiere_id,
                'mouvement_id' => $mouvement_id,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification: ' . $e->getMessage());
        }
    }
    public function showPrix($matiere_id, $societe_id, Request $request): View
    {
        // Validation des filtres
        $request->validate([
            'periode' => 'nullable|in:today,week,month,3months,6months,year,custom',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        $fournisseur = Societe::whereIn('societe_type_id', ['3', '2'])->findOrFail($societe_id);
        $matiere = Matiere::with(['sousFamille', 'societe', 'standardVersion'])->findOrFail($matiere_id);

        // Récupérer tous les prix (sans filtre pour les modaux)
        $fournisseurs_prix = $matiere->prixPourSociete($societe_id)
            ->orderBy('date', 'desc')
            ->get();

        // Construire la requête avec filtres pour l'affichage
        $queryFiltered = $matiere->prixPourSociete($societe_id);

        // Appliquer les filtres de période
        if ($request->filled('periode')) {
            $periode = $request->input('periode');

            switch ($periode) {
                case 'today':
                    $queryFiltered->whereDate('date', today());
                    break;
                case 'week':
                    $queryFiltered->where('date', '>=', now()->startOfWeek());
                    break;
                case 'month':
                    $queryFiltered->where('date', '>=', now()->startOfMonth());
                    break;
                case '3months':
                    $queryFiltered->where('date', '>=', now()->subMonths(3));
                    break;
                case '6months':
                    $queryFiltered->where('date', '>=', now()->subMonths(6));
                    break;
                case 'year':
                    $queryFiltered->where('date', '>=', now()->startOfYear());
                    break;
                case 'custom':
                    if ($request->filled('date_debut')) {
                        $queryFiltered->whereDate('date', '>=', $request->input('date_debut'));
                    }
                    if ($request->filled('date_fin')) {
                        $queryFiltered->whereDate('date', '<=', $request->input('date_fin'));
                    }
                    break;
            }
        }

        $fournisseurs_prix_filtered = $queryFiltered->orderBy('date', 'desc')->get();

        // Données pour le graphique
        $dates_filtered = $fournisseurs_prix_filtered->sortBy('date')->pluck('date');
        $prix_filtered = $fournisseurs_prix_filtered->sortBy('date')->pluck('prix_unitaire');

        return view('matieres.show_prix', [
            'matiere' => $matiere,
            'fournisseur' => $fournisseur,
            'fournisseurs_prix' => $fournisseurs_prix,
            'fournisseurs_prix_filtered' => $fournisseurs_prix_filtered,
            'dates_filtered' => $dates_filtered,
            'prix_filtered' => $prix_filtered,
        ]);
    }

    /**
     * Affiche le formulaire d'import de matières par Excel
     */
    public function importForm()
    {
        return view('matieres.import');
    }

    /**
     * Télécharge un fichier CSV d'exemple pour l'import (version simplifiée)
     */
    public function importExample()
    {
        $headers = [
            'ref_interne',
            'designation',
            'unite',
            'sous_famille',
            'dn(optionnel)',
            'epaisseur(optionnel)',
            'standard(optionnel)',
            'ref_valeur_unitaire(rien ou valeur de ref unitaire (conditionnement))',
            'materiau(optionnel)',
            'prix(optionnel & seulement si fournisseur)'
        ];
        $example = ['AA-00001', 'TUBE', 'ml', 'Tubes acier', '25', '2.5', 'NF EN 10255', '6', 'Acier', '12.5'];
        // Générer le CSV en mémoire avec ; comme séparateur
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, $headers, ";");
        fputcsv($handle, $example, ";");
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        // Concaténer BOM + sep=; + retour ligne + CSV
        $output = "\xEF\xBB\xBFsep=;\r\n" . $csv;
        return response($output)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="exemple_import_matieres.csv"');
    }

    /**
     * Traite le fichier CSV et affiche un aperçu pour validation (version simplifiée)
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'fournisseur_id' => 'nullable|integer|exists:societes,id',
        ]);
        // Vérifier que le fichier est un CSV
        if (!$request->file('file')->isValid() || $request->file('file')->getClientOriginalExtension() !== 'csv') {
            return back()->withErrors(['file' => 'Le fichier doit être un CSV valide.']);
        }
        $path = $request->file('file')->getRealPath();
        $rows = [];
        $handle = fopen($path, 'r');
        // Colonnes attendues dans l'ordre strict
        $expectedHeaders = [
            'ref_interne',
            'designation',
            'unite',
            'sous_famille',
            'dn',
            'epaisseur',
            'standard',
            'ref_valeur_unitaire',
            'materiau',
            'prix'
        ];
        $firstLine = fgetcsv($handle, 0, ';');
        // On ignore la première ligne si c'est sep=;
        if ($firstLine && stripos($firstLine[0], 'sep=') === 0) {
            $firstLine = fgetcsv($handle, 0, ';');
        }
        // Si la première ligne ressemble à des headers utilisateur, on l'ignore
        if ($firstLine && preg_match('/réf|ref|désignation|designation|unité|unite|famille|prix/i', implode(' ', $firstLine))) {
            // On saute la ligne
        } else if ($firstLine) {
            // Sinon, c'est une vraie donnée
            $data = $firstLine;
            $row = [];
            foreach ($expectedHeaders as $k => $col) {
                $row[$col] = $data[$k] ?? '';
            }
            $rows[] = $row;
        }
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $row = [];
            foreach ($expectedHeaders as $k => $col) {
                $row[$col] = $data[$k] ?? '';
            }
            $rows[] = $row;
        }
        fclose($handle);

        // Récupérer les ref_interne et designation déjà existants en base
        $existingRefs = \App\Models\Matiere::pluck('ref_interne')->map(function ($v) {
            return mb_strtolower(trim($v));
        })->toArray();

        // Pour détecter les doublons dans le fichier importé
        $seenRefs = [];

        // Préparer les listes de référence pour matching
        $unites = \App\Models\Unite::all();
        $sous_familles = \App\Models\SousFamille::all();
        $standards = \App\Models\Standard::all();
        $materials = \App\Models\Material::all();
        $normalize = function ($str) {
            if (!is_string($str)) return '';
            return strtolower(preg_replace('/[\p{Mn}]/u', '', \Normalizer::normalize($str, \Normalizer::FORM_D)));
        };
        $preview = [];
        foreach ($rows as $i => $row) {
            $previewRow = [];
            // Unité
            if (isset($row['unite'])) {
                $val = $normalize($row['unite']);
                if ($val === 'PCE') {
                    $val = 'u'; // Normaliser PCE en u
                }
                $found = $unites->first(function ($u) use ($val, $normalize) {
                    return $normalize($u->short) === $val || $normalize($u->full) === $val;
                });
                $previewRow['unite'] = [
                    'value' => $row['unite'],
                    'id' => $found ? $found->id : null,
                    'label' => $found ? ($found->short . ' (ID ' . $found->id . ')') : null,
                    'error' => $found ? null : 'Unité non trouvée'
                ];
            }
            // Sous-famille
            if (isset($row['sous_famille'])) {
                $val = $normalize($row['sous_famille']);
                $found = $sous_familles->first(function ($sf) use ($val, $normalize) {
                    return $normalize($sf->nom) === $val;
                });
                $previewRow['sous_famille'] = [
                    'value' => $row['sous_famille'],
                    'id' => $found ? $found->id : null,
                    'label' => $found ? ($found->nom . ' (ID ' . $found->id . ')') : null,
                    'error' => $found ? null : 'Sous-famille non trouvée'
                ];
            }
            // Standard
            if (isset($row['standard'])) {
                $val = $normalize($row['standard']);
                $found = $standards->first(function ($s) use ($val, $normalize) {
                    return $normalize($s->nom) === $val;
                });
                $previewRow['standard'] = [
                    'value' => $row['standard'],
                    'id' => $found ? $found->id : null,
                    'label' => $found ? ($found->nom . ' (ID ' . $found->id . ')') : null,
                    'error' => $found ? null : 'Standard non trouvé'
                ];
            }
            // Matériau
            if (isset($row['materiau'])) {
                $val = $normalize($row['materiau']);
                $found = $materials->first(function ($m) use ($val, $normalize) {
                    return $normalize($m->nom) === $val;
                });
                $previewRow['materiau'] = [
                    'value' => $row['materiau'],
                    'id' => $found ? $found->id : null,
                    'label' => $found ? ($found->nom . ' (ID ' . $found->id . ')') : null,
                    'error' => $found ? null : 'Matériau non trouvé'
                ];
            }
            // Autres champs (ref_interne, designation, etc.)
            foreach ($row as $col => $val) {
                if (!isset($previewRow[$col])) {
                    $error = null;
                    $duplicate_line = null;
                    $exists_in_db = false;
                    if (empty($val) && in_array($col, ['ref_interne', 'designation'])) {
                        $error = 'Obligatoire';
                    }
                    // Vérification unicité ref_interne
                    if ($col === 'ref_interne' && !empty($val)) {
                        $valNorm = mb_strtolower(trim($val));
                        if (in_array($valNorm, $existingRefs)) {
                            $error = 'Déjà existant en base';
                            $exists_in_db = true;
                        } elseif (isset($seenRefs[$valNorm])) {
                            $error = 'Doublon dans le fichier (ligne ' . ($seenRefs[$valNorm] + 1) . ')';
                            $duplicate_line = $seenRefs[$valNorm] + 1;
                        } else {
                            $seenRefs[$valNorm] = $i;
                        }
                    }

                    $previewRow[$col] = [
                        'value' => $val,
                        'id' => null,
                        'label' => null,
                        'error' => $error,
                        'duplicate_line' => $duplicate_line,
                        'exists_in_db' => $exists_in_db,
                    ];
                }
            }
            $preview[] = $previewRow;
        }
        return view('matieres.import_preview', [
            'headers' => $expectedHeaders,
            'rows' => $rows,
            'preview' => $preview,
            'fournisseur_id' => $request->input('fournisseur_id'),
            'prix_fournisseur' => $request->input('prix_fournisseur'),
        ]);
    }

    /**
     * Traite l'import effectif des matières depuis la prévisualisation
     */
    public function importExcelStore(Request $request)
    {
        $rows = json_decode($request->input('rows'), true);
        $fournisseur_id = $request->input('fournisseur_id');
        $created = [];
        $createdPrix = [];
        // On ne prend que les lignes valides (ref_interne, designation, unite, sous_famille)
        foreach ($rows as $i => $row) {
            $critCols = ['ref_interne', 'designation', 'unite', 'sous_famille'];
            $hasError = false;
            foreach ($critCols as $col) {
                if (empty($row[$col])) {
                    $hasError = true;
                    break;
                }
            }
            if ($hasError) continue;
            // Vérifier unicité ref_interne et designation
            if (Matiere::where('ref_interne', $row['ref_interne'])->exists()) continue;

            // Normalisation des champs numériques/clé étrangère
            foreach (['dn', 'epaisseur', 'ref_valeur_unitaire', 'material_id'] as $field) {
                if (!isset($row[$field]) || $row[$field] === '' || $row[$field] === null) {
                    $row[$field] = null;
                }
            }

            // Matching des IDs
            $unite = null;
            if (!empty($row['unite'])) {
                if ($row['unite'] === 'PCE') {
                    $row['unite'] = 'u'; // Normaliser PCE en u
                }
                $unite = Unite::where(function($query) use ($row) {
                    $term = $row['unite'];
                    $query->where('short', 'ILIKE', "{$term}")
                          ->orWhere('full', 'ILIKE', "{$term}");
                })->first();
            }
            $sous_famille = !empty($row['sous_famille']) ? \App\Models\SousFamille::where('nom', 'ILIKE', '%' . $row['sous_famille'] . '%')->first() : null;
            if ($sous_famille == null) {
                continue;
            }
            $standard = !empty($row['standard'])
                ? \App\Models\Standard::whereRaw("unaccent(nom) ILIKE unaccent(?)", ["%{$row['standard']}%"])->first()
                : null;
            $material = !empty($row['materiau'])
                ? \App\Models\Material::whereRaw("unaccent(nom) ILIKE unaccent(?)", ["%{$row['materiau']}%"])->first()
                : null;
            // Création de la matière
            try {
                $matiere = \App\Models\Matiere::create([
                    'ref_interne' => $row['ref_interne'],
                    'designation' => $row['designation'],
                    'unite_id' => $unite ? $unite->id : null,
                    'sous_famille_id' => $sous_famille ? $sous_famille->id : null,
                    'dn' => $row['dn'],
                    'epaisseur' => $row['epaisseur'],
                    'standard_id' => $standard ? $standard->getLatestVersion()->id : null,
                    'ref_valeur_unitaire' => $row['ref_valeur_unitaire'],
                    'material_id' => $material ? $material->id : null,
                    'prix_moyen' => null,
                    'date_dernier_achat' => null,
                    'quantite' => 0,
                    'stock_min' => 0,
                ]);
            } catch (\Throwable $e) {
                \Log::error('Erreur lors de la création de la matière à l\'import (ligne ' . ($i+1) . ')', [
                    'row' => $row,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                continue;
            }
            $created[] = $matiere;
            // Si fournisseur sélectionné, créer la liaison
            if ($fournisseur_id) {
                $societeMatiere = SocieteMatiere::create([
                    'matiere_id' => $matiere->id,
                    'societe_id' => $fournisseur_id,
                ]);
                // Si prix fourni, créer le prix
                if (!empty($row['prix'])) {
                    $createdPrix[] = \App\Models\SocieteMatierePrix::create([
                        'societe_matiere_id' => $societeMatiere->id,
                        'prix_unitaire' => str_replace(',', '.', $row['prix']),
                        'date' => now(),
                    ]);
                }
            }
        }
        return redirect()->route('matieres.index')->with('success', count($created) . ' matières importées avec succès. <br/>'.count($createdPrix).' prix ajoutés avec succès');
    }

    /**
     * Affiche le formulaire d'import de base de données XLSX
     */
    public function importDatabaseForm()
    {
        return view('matieres.import_database');
    }

    /**
     * Génère une prévisualisation de l'import de base de données depuis un fichier XLSX
     */
    public function importDatabase(Request $request)
    {
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');
            $data = Excel::toArray([], $file);

            if (empty($data) || empty($data[0])) {
                return back()->withErrors(['file' => 'Le fichier est vide ou invalide.']);
            }

            $sheet = $data[0];

            // Log pour debugging - afficher les 6 premières lignes pour localiser les en-têtes
            \Log::info('Import XLSX - Échantillon de données (6 premières lignes)', [
                'total_lignes' => count($sheet),
                'ligne_1' => $sheet[0] ?? null,
                'ligne_2' => $sheet[1] ?? null,
                'ligne_3' => $sheet[2] ?? null,
                'ligne_4' => $sheet[3] ?? null,
                'ligne_5' => $sheet[4] ?? null,
                'ligne_6' => $sheet[5] ?? null,
            ]);

            // Fonction de normalisation
            $normalize = function ($str) {
                if (!is_string($str)) return '';
                return strtolower(preg_replace('/[\p{Mn}]/u', '', \Normalizer::normalize($str, \Normalizer::FORM_D)));
            };

            // Charger les données de référence
            $unites = \App\Models\Unite::all();
            $unitesIndex = [];
            foreach ($unites as $unite) {
                $unitesIndex[$normalize($unite->short)] = $unite;
                $unitesIndex[$normalize($unite->full)] = $unite;
            }

            $familles = \App\Models\Famille::all();
            $famillesIndex = [];
            foreach ($familles as $famille) {
                $famillesIndex[$normalize($famille->nom)] = $famille;
            }

            $sous_familles = \App\Models\SousFamille::all();
            $sousFamillesIndex = [];
            $sousFamillesByFamille = [];
            foreach ($sous_familles as $sf) {
                $sousFamillesIndex[$normalize($sf->nom)] = $sf;
                if (!isset($sousFamillesByFamille[$sf->famille_id])) {
                    $sousFamillesByFamille[$sf->famille_id] = $sf;
                }
            }

            $standards = \App\Models\Standard::all();
            $standardsIndex = [];
            foreach ($standards as $standard) {
                $standardsIndex[$normalize($standard->nom)] = $standard;
            }

            $materials = \App\Models\Material::all();
            $materialsIndex = [];
            foreach ($materials as $material) {
                $materialsIndex[$normalize($material->nom)] = $material;
            }

            $fournisseurs = \App\Models\Societe::whereIn('societe_type_id', ['2','3'])->get();
            $fournisseursIndex = [];
            foreach ($fournisseurs as $fournisseur) {
                $fournisseursIndex[$normalize($fournisseur->raison_sociale)] = $fournisseur;
                if ($fournisseur->nom) {
                    $fournisseursIndex[$normalize($fournisseur->nom)] = $fournisseur;
                }
            }

            // Charger les références existantes
            $existingRefs = \App\Models\Matiere::pluck('ref_interne')->map(function ($v) {
                return mb_strtolower(trim($v));
            })->flip()->toArray();

            // Détecter les en-têtes (ligne 5, index 4)
            $headers = array_map('trim', $sheet[4] ?? []);
            $headerMapping = $this->mapDatabaseHeaders($headers);

            // Log pour debugging
            \Log::info('Import XLSX - Headers détectés', [
                'headers_bruts' => $headers,
                'mapping' => $headerMapping,
                'note' => 'En-têtes lus depuis la ligne 5, données à partir de la ligne 6',
            ]);

            // Définir les colonnes pour la prévisualisation
            $columns = ['ref_interne', 'famille', 'materiau', 'fournisseur', 'designation', 'standard', 'dn', 'epaisseur', 'unite', 'ref_valeur_unitaire', 'prix'];

            // Préparer les données pour la prévisualisation
            $rows = [];
            $preview = [];

            // Traiter chaque ligne (commence à la ligne 6, index 5)
            for ($i = 5; $i < count($sheet); $i++) {
                $rowData = $sheet[$i];

                // Ignorer les lignes vides
                if (empty(array_filter($rowData))) {
                    continue;
                }

                // Mapper les données selon les en-têtes
                $row = [];
                foreach ($columns as $col) {
                    $row[$col] = '';
                }

                foreach ($headerMapping as $index => $columnName) {
                    if (isset($row[$columnName])) {
                        $row[$columnName] = isset($rowData[$index]) ? trim($rowData[$index]) : '';
                    }
                }

                $rows[] = $row;

                // Générer les informations de prévisualisation avec validation
                $previewRow = [];

                // Vérifier ref_interne
                if (empty($row['ref_interne'])) {
                    $previewRow['ref_interne'] = ['error' => 'Référence interne obligatoire'];
                } else {
                    $refNormalized = mb_strtolower(trim($row['ref_interne']));
                    if (isset($existingRefs[$refNormalized])) {
                        $previewRow['ref_interne'] = ['error' => 'Référence déjà existante'];
                    } else {
                        $previewRow['ref_interne'] = ['id' => true];
                        $existingRefs[$refNormalized] = true; // Éviter les doublons dans le fichier
                    }
                }

                // Vérifier designation
                if (empty($row['designation'])) {
                    $previewRow['designation'] = ['error' => 'Désignation obligatoire'];
                } else {
                    $previewRow['designation'] = ['id' => true];
                }

                // Vérifier unité
                if (empty($row['unite'])) {
                    $previewRow['unite'] = ['error' => 'Unité obligatoire'];
                } else {
                    $val = $normalize($row['unite']);
                    if ($val === 'pce') $val = 'u';
                    $unite = $unitesIndex[$val] ?? null;
                    if ($unite) {
                        $previewRow['unite'] = ['id' => $unite->id, 'label' => $unite->short];
                    } else {
                        $previewRow['unite'] = ['error' => 'Unité non trouvée'];
                    }
                }

                // Vérifier famille/sous-famille
                $sous_famille = null;
                if (!empty($row['famille'])) {
                    $val = $normalize($row['famille']);
                    $famille = $famillesIndex[$val] ?? null;

                    if ($famille) {
                        $sous_famille = $sousFamillesByFamille[$famille->id] ?? null;
                        if ($sous_famille) {
                            $previewRow['famille'] = ['id' => $sous_famille->id, 'label' => $famille->nom . ' → ' . $sous_famille->nom];
                        } else {
                            $previewRow['famille'] = ['error' => 'Aucune sous-famille trouvée pour cette famille'];
                        }
                    } else {
                        $previewRow['famille'] = ['error' => 'Famille non trouvée'];
                    }
                } else {
                    $previewRow['famille'] = ['error' => 'Famille obligatoire'];
                }

                // Vérifier matériau (optionnel)
                if (!empty($row['materiau'])) {
                    $val = $normalize($row['materiau']);
                    $material = $materialsIndex[$val] ?? null;
                    if ($material) {
                        $previewRow['materiau'] = ['id' => $material->id, 'label' => $material->nom];
                    } else {
                        $previewRow['materiau'] = ['error' => 'Matériau non trouvé (sera ignoré)'];
                    }
                } else {
                    $previewRow['materiau'] = ['id' => null];
                }

                // Vérifier standard (optionnel)
                if (!empty($row['standard'])) {
                    $val = $normalize($row['standard']);
                    $standard = $standardsIndex[$val] ?? null;
                    if ($standard) {
                        $previewRow['standard'] = ['id' => $standard->id, 'label' => $standard->nom];
                    } else {
                        $previewRow['standard'] = ['error' => 'Standard non trouvé (sera ignoré)'];
                    }
                } else {
                    $previewRow['standard'] = ['id' => null];
                }

                // Vérifier fournisseur (optionnel)
                if (!empty($row['fournisseur'])) {
                    $val = $normalize($row['fournisseur']);
                    $fournisseur = $fournisseursIndex[$val] ?? null;
                    if ($fournisseur) {
                        $previewRow['fournisseur'] = ['id' => $fournisseur->id, 'label' => $fournisseur->raison_sociale];
                    } else {
                        $previewRow['fournisseur'] = ['error' => 'Fournisseur non trouvé (sera ignoré)'];
                    }
                } else {
                    $previewRow['fournisseur'] = ['id' => null];
                }

                // DN et épaisseur (optionnels)
                $previewRow['dn'] = ['id' => !empty($row['dn'])];
                $previewRow['epaisseur'] = ['id' => !empty($row['epaisseur'])];
                $previewRow['ref_valeur_unitaire'] = ['id' => !empty($row['ref_valeur_unitaire'])];

                // Prix (optionnel)
                if (!empty($row['prix'])) {
                    $previewRow['prix'] = ['id' => true, 'label' => $row['prix']];
                } else {
                    $previewRow['prix'] = ['id' => null];
                }

                $preview[] = $previewRow;
            }

            // Log pour debugging
            \Log::info('Import XLSX - Résultats du traitement', [
                'total_lignes_fichier' => count($sheet) - 1,
                'lignes_traitees' => count($rows),
                'lignes_preview' => count($preview),
            ]);

            // Si aucune ligne valide n'a été trouvée
            if (empty($rows)) {
                return back()->withErrors(['file' => 'Aucune ligne valide trouvée dans le fichier. Vérifiez que le format correspond aux spécifications.']);
            }

            return view('matieres.import_database_preview', [
                'headers' => $columns,
                'rows' => $rows,
                'preview' => $preview,
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la prévisualisation de l\'import XLSX', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['file' => 'Erreur lors de la prévisualisation : ' . $e->getMessage()]);
        }
    }

    /**
     * Traite l'import effectif de la base de données depuis la prévisualisation
     */
    public function importDatabaseStore(Request $request)
    {
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');

        $rows = json_decode($request->input('rows'), true);

        if (empty($rows)) {
            return redirect()->route('matieres.index')->withErrors(['error' => 'Aucune donnée à importer.']);
        }

        $created = 0;
        $errors = 0;
        $skipped = 0;

        // Fonction de normalisation
        $normalize = function ($str) {
            if (!is_string($str)) return '';
            return strtolower(preg_replace('/[\p{Mn}]/u', '', \Normalizer::normalize($str, \Normalizer::FORM_D)));
        };

        // Charger les données de référence
        $unites = \App\Models\Unite::all();
        $unitesIndex = [];
        foreach ($unites as $unite) {
            $unitesIndex[$normalize($unite->short)] = $unite;
            $unitesIndex[$normalize($unite->full)] = $unite;
        }

        $familles = \App\Models\Famille::all();
        $famillesIndex = [];
        foreach ($familles as $famille) {
            $famillesIndex[$normalize($famille->nom)] = $famille;
        }

        $sous_familles = \App\Models\SousFamille::all();
        $sousFamillesIndex = [];
        $sousFamillesByFamille = [];
        foreach ($sous_familles as $sf) {
            $sousFamillesIndex[$normalize($sf->nom)] = $sf;
            if (!isset($sousFamillesByFamille[$sf->famille_id])) {
                $sousFamillesByFamille[$sf->famille_id] = $sf;
            }
        }

        $standards = \App\Models\Standard::all();
        $standardsIndex = [];
        foreach ($standards as $standard) {
            $standardsIndex[$normalize($standard->nom)] = $standard;
        }

        $materials = \App\Models\Material::all();
        $materialsIndex = [];
        foreach ($materials as $material) {
            $materialsIndex[$normalize($material->nom)] = $material;
        }

        $fournisseurs = \App\Models\Societe::whereIn('societe_type_id', ['2','3'])->get();
        $fournisseursIndex = [];
        foreach ($fournisseurs as $fournisseur) {
            $fournisseursIndex[$normalize($fournisseur->raison_sociale)] = $fournisseur;
            if ($fournisseur->nom) {
                $fournisseursIndex[$normalize($fournisseur->nom)] = $fournisseur;
            }
        }

        // Charger les références existantes
        $existingRefs = \App\Models\Matiere::pluck('ref_interne')->map(function ($v) {
            return mb_strtolower(trim($v));
        })->flip()->toArray();

        // Traiter chaque ligne
        foreach ($rows as $i => $row) {
            // Vérifier les champs obligatoires
            if (empty($row['ref_interne']) || empty($row['designation']) || empty($row['unite'])) {
                $errors++;
                continue;
            }

            // Vérifier unicité
            $refNormalized = mb_strtolower(trim($row['ref_interne']));
            if (isset($existingRefs[$refNormalized])) {
                $skipped++;
                continue;
            }
            $existingRefs[$refNormalized] = true;

            // Trouver l'unité
            $val = $normalize($row['unite']);
            if ($val === 'pce') $val = 'u';
            $unite = $unitesIndex[$val] ?? null;
            if (!$unite) {
                $errors++;
                continue;
            }

            // Trouver la sous-famille
            $sous_famille = null;
            if (!empty($row['famille'])) {
                $val = $normalize($row['famille']);
                $famille = $famillesIndex[$val] ?? null;
                if ($famille) {
                    $sous_famille = $sousFamillesByFamille[$famille->id] ?? null;
                }
            }

            if (!$sous_famille) {
                $errors++;
                continue;
            }

            // Trouver le standard
            $standard = null;
            if (!empty($row['standard'])) {
                $val = $normalize($row['standard']);
                $standard = $standardsIndex[$val] ?? null;
            }

            // Trouver le matériau
            $material = null;
            if (!empty($row['materiau'])) {
                $val = $normalize($row['materiau']);
                $material = $materialsIndex[$val] ?? null;
            }

            // Trouver le fournisseur
            $fournisseur = null;
            if (!empty($row['fournisseur'])) {
                $val = $normalize($row['fournisseur']);
                $fournisseur = $fournisseursIndex[$val] ?? null;
            }

            // Créer la matière
            try {
                $matiere = \App\Models\Matiere::create([
                    'ref_interne' => $row['ref_interne'],
                    'designation' => $row['designation'],
                    'unite_id' => $unite->id,
                    'sous_famille_id' => $sous_famille->id,
                    'dn' => $row['dn'] ?? null,
                    'epaisseur' => $row['epaisseur'] ?? null,
                    'standard_id' => $standard ? $standard->getLatestVersion()->id : null,
                    'ref_valeur_unitaire' => $row['ref_valeur_unitaire'] ?? null,
                    'material_id' => $material ? $material->id : null,
                    'prix_moyen' => null,
                    'date_dernier_achat' => null,
                    'quantite' => 0,
                    'stock_min' => 0,
                ]);

                // Créer la liaison avec le fournisseur si présent
                if ($fournisseur && !empty($row['prix'])) {
                    $societeMatiere = SocieteMatiere::create([
                        'matiere_id' => $matiere->id,
                        'societe_id' => $fournisseur->id,
                    ]);

                    \App\Models\SocieteMatierePrix::create([
                        'societe_matiere_id' => $societeMatiere->id,
                        'prix_unitaire' => str_replace(',', '.', $row['prix']),
                        'date' => now(),
                    ]);
                }

                $created++;
            } catch (\Throwable $e) {
                \Log::error('Erreur lors de l\'import de base de données (ligne ' . ($i + 1) . ')', [
                    'row' => $row,
                    'exception' => $e->getMessage(),
                ]);
                $errors++;
            }
        }

        $message = "$created matières importées avec succès.";
        if ($skipped > 0) {
            $message .= " <br/>$skipped matières ignorées (déjà existantes).";
        }
        if ($errors > 0) {
            $message .= " <br/>$errors lignes avec erreurs.";
        }

        return redirect()->route('matieres.index')->with('success', $message);
    }

    /**
     * Mapping des en-têtes pour l'import de base de données
     */
    private function mapDatabaseHeaders($headers)
    {
        $mapping = [];
        $normalize = function ($str) {
            return mb_strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $str)));
        };

        foreach ($headers as $index => $header) {
            $normalized = $normalize($header);
            $found = false;

            // Log pour debugging
            \Log::debug("Header #{$index}: '{$header}' -> normalisé: '{$normalized}'");

            if (preg_match('/ref.*interne|reference/i', $normalized)) {
                $mapping[$index] = 'ref_interne';
                $found = true;
            } elseif (preg_match('/fournisseur/i', $normalized)) {
                $mapping[$index] = 'fournisseur';
                $found = true;
            } elseif (preg_match('/famille/i', $normalized) && !preg_match('/sous/i', $normalized)) {
                $mapping[$index] = 'famille';
                $found = true;
            } elseif (preg_match('/sous.*famille/i', $normalized)) {
                $mapping[$index] = 'sous_famille';
                $found = true;
            } elseif (preg_match('/matiere|materiau|material/i', $normalized)) {
                $mapping[$index] = 'materiau';
                $found = true;
            } elseif (preg_match('/designation/i', $normalized)) {
                $mapping[$index] = 'designation';
                $found = true;
            } elseif (preg_match('/standar/i', $normalized)) {
                $mapping[$index] = 'standard';
                $found = true;
            } elseif (preg_match('/\bdn\b/i', $normalized)) {
                $mapping[$index] = 'dn';
                $found = true;
            } elseif (preg_match('/\bep\b|epaisseur/i', $normalized)) {
                $mapping[$index] = 'epaisseur';
                $found = true;
            } elseif (preg_match('/unite/i', $normalized)) {
                $mapping[$index] = 'unite';
                $found = true;
            } elseif (preg_match('/longueur|ref.*valeur.*unitaire/i', $normalized)) {
                $mapping[$index] = 'ref_valeur_unitaire';
                $found = true;
            } elseif (preg_match('/prix/i', $normalized)) {
                $mapping[$index] = 'prix';
                $found = true;
            }

            if ($found) {
                \Log::debug("  -> Mappé à: {$mapping[$index]}");
            } else {
                \Log::debug("  -> Non mappé (ignoré)");
            }
        }

        return $mapping;
    }
}

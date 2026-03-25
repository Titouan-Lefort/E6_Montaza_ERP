<?php

namespace App\Http\Controllers;

use App\Models\DossierDevis;
use App\Models\DossierDevisQuantitatif;
use App\Models\Affaire;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DossierDevisController extends Controller
{
    /**
     * Liste des dossiers de devis
     */
    public function index(Request $request)
    {
        $query = DossierDevis::with(['affaire', 'societe', 'createur'])
            ->where('statut', '!=', DossierDevis::STATUT_ARCHIVE);

        // Recherche
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('nom', 'LIKE', "%{$search}%")
                  ->orWhere('reference_projet', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($statut = $request->get('statut')) {
            $query->where('statut', $statut);
        }

        $dossiers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('dossiers_devis.index', compact('dossiers'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create(Request $request)
    {
        $code = DossierDevis::generateNextCode();
        $affaire_id = $request->query('affaire_id');

        $affaires = Affaire::where('statut', '!=', Affaire::STATUT_ARCHIVE)
            ->orderBy('created_at', 'desc')
            ->get();

        $societes = Societe::clients()->orderBy('raison_sociale')->get();

        return view('dossiers_devis.create', compact('code', 'affaire_id', 'affaires', 'societes'));
    }

    /**
     * Enregistre un nouveau dossier de devis
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:dossiers_devis,code',
            'nom' => 'required|string|max:255',
            'affaire_id' => 'nullable|exists:affaires,id',
            'societe_id' => 'nullable|exists:societes,id',
            'societe_contact_id' => 'nullable|exists:societe_contacts,id',
            'reference_projet' => 'nullable|string|max:255',
            'lieu_intervention' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date_creation' => 'required|date',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['statut'] = DossierDevis::STATUT_QUANTITATIF;

        $dossier = DossierDevis::create($validated);

        return redirect()->route('dossiers_devis.show', $dossier)
            ->with('success', 'Dossier de devis créé avec succès. Vous pouvez maintenant ajouter le quantitatif.');
    }

    /**
     * Affiche un dossier de devis
     */
    public function show(DossierDevis $dossierDevis)
    {
        $dossierDevis->load(['affaire', 'societe', 'societeContact', 'quantitatifs.matiere.unite', 'devisTuyauteries', 'createur']);

        // Charger toutes les matières avec leurs unités pour la recherche
        $matieres = \App\Models\Matiere::with('unite')
            ->orderBy('designation')
            ->get()
            ->map(function($matiere) {
                return [
                    'id' => $matiere->id,
                    'designation' => $matiere->designation,
                    'ref_interne' => $matiere->ref_interne,
                    'unite' => $matiere->unite?->nom ?? 'u',
                ];
            });

        $designations_standards = $this->getDesignationsStandards();

        return view('dossiers_devis.show', compact('dossierDevis', 'matieres', 'designations_standards'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(DossierDevis $dossierDevis)
    {
        $affaires = Affaire::where('statut', '!=', Affaire::STATUT_ARCHIVE)
            ->orderBy('created_at', 'desc')
            ->get();

        $societes = Societe::clients()->orderBy('raison_sociale')->get();

        return view('dossiers_devis.edit', compact('dossierDevis', 'affaires', 'societes'));
    }

    /**
     * Met à jour un dossier de devis
     */
    public function update(Request $request, DossierDevis $dossierDevis)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'affaire_id' => 'nullable|exists:affaires,id',
            'societe_id' => 'nullable|exists:societes,id',
            'societe_contact_id' => 'nullable|exists:societe_contacts,id',
            'reference_projet' => 'nullable|string|max:255',
            'lieu_intervention' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|in:quantitatif,en_devis,valide,archive',
        ]);

        $dossierDevis->update($validated);

        return redirect()->route('dossiers_devis.show', $dossierDevis)
            ->with('success', 'Dossier de devis mis à jour avec succès.');
    }

    /**
     * Ajoute un élément au quantitatif
     */
    public function ajouterQuantitatif(Request $request, DossierDevis $dossierDevis)
    {
        $validated = $request->validate([
            'matiere_id' => 'nullable|exists:matieres,id',
            'categorie' => 'nullable|string|max:255',
            'type' => 'required|string|in:fourniture,main_d_oeuvre,sous_traitance,consommable',
            'designation' => 'required|string|max:255',
            'description_technique' => 'nullable|string',
            'reference' => 'nullable|string|max:255',
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:50',
            'quantite_matiere_unitaire' => 'nullable|numeric|min:0',
            'unite_matiere' => 'nullable|string|max:50',
            'prix_achat' => 'nullable|numeric|min:0',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'remarques' => 'nullable|string',
        ]);

        // Déterminer l'ordre
        $maxOrdre = $dossierDevis->quantitatifs()->max('ordre') ?? 0;
        $validated['ordre'] = $maxOrdre + 1;

        $dossierDevis->quantitatifs()->create($validated);

        return back()->with('success', 'Élément ajouté au quantitatif.');
    }

    /**
     * Met à jour un élément du quantitatif
     */
    public function updateQuantitatif(Request $request, DossierDevisQuantitatif $quantitatif)
    {
        $validated = $request->validate([
            'matiere_id' => 'nullable|exists:matieres,id',
            'categorie' => 'nullable|string|max:255',
            'type' => 'required|string|in:fourniture,main_d_oeuvre,sous_traitance,consommable',
            'designation' => 'required|string|max:255',
            'description_technique' => 'nullable|string',
            'reference' => 'nullable|string|max:255',
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:50',
            'quantite_matiere_unitaire' => 'nullable|numeric|min:0',
            'unite_matiere' => 'nullable|string|max:50',
            'prix_achat' => 'nullable|numeric|min:0',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'remarques' => 'nullable|string',
        ]);

        $quantitatif->update($validated);

        return back()->with('success', 'Élément mis à jour.');
    }

    /**
     * Supprime un élément du quantitatif
     */
    public function deleteQuantitatif(DossierDevisQuantitatif $quantitatif)
    {
        $quantitatif->delete();

        return back()->with('success', 'Élément supprimé du quantitatif.');
    }

    /**
     * Affiche la page de préparation du devis
     */
    public function preparerDevis(DossierDevis $dossierDevis)
    {
        $dossierDevis->load(['quantitatifs.matiere']);

        return view('dossiers_devis.preparer_devis', compact('dossierDevis'));
    }

    /**
     * Génère un devis à partir du quantitatif
     */
    public function genererDevis(Request $request, DossierDevis $dossierDevis)
    {
        $validated = $request->validate([
            'quantitatifs' => 'required|array',
            'quantitatifs.*.lot' => 'required|integer|min:1|max:5',
            'lot_titles' => 'required|array',
            'lot_titles.*' => 'nullable|string|max:255',
        ]);

        // Organiser les quantitatifs par lot
        $lotAssignments = [];
        foreach ($validated['quantitatifs'] as $quantitatifId => $data) {
            $lotNum = $data['lot'];
            if (!isset($lotAssignments[$lotNum])) {
                $lotAssignments[$lotNum] = [];
            }
            $lotAssignments[$lotNum][] = $quantitatifId;
        }

        // Créer le devis
        $devis = \App\Models\DevisTuyauterie::create([
            'affaire_id' => $dossierDevis->affaire_id,
            'dossier_devis_id' => $dossierDevis->id,
            'societe_id' => $dossierDevis->societe_id,
            'societe_contact_id' => $dossierDevis->societe_contact_id,
            'client_nom' => $dossierDevis->societe?->raison_sociale,
            'client_contact' => $dossierDevis->societeContact ? $dossierDevis->societeContact->prenom . ' ' . $dossierDevis->societeContact->nom : null,
            'client_adresse' => $dossierDevis->societe?->adresse,
            'reference_projet' => $dossierDevis->nom,
            'date_emission' => now(),
            'duree_validite' => 30,
            'conditions_paiement' => '30% à la commande, solde à réception.',
            'delais_execution' => 'À définir selon planning.',
            'options' => [
                'essais_hydrauliques' => false,
                'ressuage' => false,
                'radiographie' => false,
                'dossier_fin_travaux' => true,
                'cahier_soudage' => false,
                'certificats_matiere' => true,
                'nacelle' => false,
                'echafaudage' => false,
                'levage' => false,
                'frais_consommables_forfait' => 0,
            ],
        ]);

        // Créer les sections avec les lignes
        ksort($lotAssignments); // Trier les lots par numéro

        foreach ($lotAssignments as $lotNum => $quantitatifIds) {
            // Créer la section
            $section = $devis->sections()->create([
                'titre' => $validated['lot_titles'][$lotNum] ?? "Lot $lotNum",
                'ordre' => $lotNum,
            ]);

            // Créer les lignes pour cette section
            $quantitatifs = \App\Models\DossierDevisQuantitatif::whereIn('id', $quantitatifIds)
                ->orderBy('ordre')
                ->get();

            foreach ($quantitatifs as $index => $quantitatif) {
                $section->lignes()->create([
                    'ordre' => $index + 1,
                    'type' => $quantitatif->type,
                    'designation' => $quantitatif->description_technique ?? $quantitatif->designation,
                    'matiere' => $quantitatif->matiere?->designation,
                    'matiere_id' => $quantitatif->matiere_id,
                    'quantite' => $quantitatif->quantite,
                    'unite' => $quantitatif->unite,
                    'quantite_matiere_unitaire' => $quantitatif->quantite_matiere_unitaire,
                    'unite_matiere' => $quantitatif->unite_matiere,
                    'prix_achat' => $quantitatif->prix_achat ?? 0,
                    'prix_unitaire' => $quantitatif->prix_unitaire ?? 0,
                    'total_ht' => ($quantitatif->quantite ?? 0) * ($quantitatif->prix_unitaire ?? 0),
                ]);
            }
        }

        return redirect()->route('devis_tuyauterie.edit', $devis->id)
            ->with('success', 'Devis généré avec succès à partir du quantitatif.');
    }

    /**
     * Archive un dossier
     */
    public function archiver(DossierDevis $dossierDevis)
    {
        $dossierDevis->update(['statut' => DossierDevis::STATUT_ARCHIVE]);

        return back()->with('success', 'Dossier archivé.');
    }

    /**
     * Delete un dossier
     */
    public function destroy(DossierDevis $dossierDevis)
    {
        $dossierDevis->delete();

        return redirect()->route('dossiers_devis.index')
            ->with('success', 'Dossier supprimé.');
    }

    /**
     * Retourne les désignations standards pour les devis
     */
    private function getDesignationsStandards()
    {
        return [
            // Tubes et tuyaux
            'Tube acier carbone soudé',
            'Tube acier carbone sans soudure',
            'Tube inox 304L',
            'Tube inox 316L',
            'Tube cuivre',
            'Tube PVC',
            'Tube PE',
            'Tube multicouche',

            // Raccords
            'Coude 90° acier soudé',
            'Coude 45° acier soudé',
            'Coude 90° inox 304L',
            'Coude 90° inox 316L',
            'Té égal acier soudé',
            'Té réduit acier soudé',
            'Réduction concentrique acier',
            'Réduction excentrique acier',
            'Bouchon acier soudé',
            'Manchon acier fileté',
            'Bride à souder acier',
            'Bride à collerette inox',
            'Contre-bride acier',

            // Vannes et robinetterie
            'Vanne à boisseau sphérique',
            'Vanne papillon',
            'Vanne à opercule',
            'Vanne guillotine',
            'Clapet anti-retour',
            'Soupape de sécurité',
            'Détendeur de pression',
            'Robinet d\'arrêt',
            'Électrovanne',

            // Brides et joints
            'Bride PN10 acier',
            'Bride PN16 acier',
            'Bride PN25 acier',
            'Bride PN40 acier',
            'Joint plat caoutchouc',
            'Joint spiral',
            'Joint graphite',
            'Boulonnerie bride',

            // Supports et fixations
            'Collier de fixation simple',
            'Collier de fixation renforcé',
            'Support à patins',
            'Support coulissant',
            'Support fixe',
            'Console murale',
            'Potence de support',
            'Amortisseur de vibrations',

            // Soudage et assemblage
            'Soudure bout à bout',
            'Soudure en angle',
            'Chanfreinage',
            'Pointage',
            'Meulage et ébavurage',
            'Traitement thermique',

            // Calorifuge et isolation
            'Calorifuge laine de roche',
            'Calorifuge mousse élastomère',
            'Tôle de protection alu',
            'Bande d\'étanchéité',
            'Peinture antirouille',
            'Peinture de finition',

            // Essais et contrôles
            'Essai hydraulique',
            'Essai pneumatique',
            'Contrôle radiographique',
            'Contrôle ultrasons',
            'Contrôle ressuage',
            'Contrôle magnétoscopie',
            'Contrôle visuel',
            'Épreuve en pression',

            // Installation et montage
            'Préfabrication en atelier',
            'Montage sur site',
            'Dépose tuyauterie existante',
            'Modification de tracé',
            'Mise en service',
            'Formation utilisateur',

            // Main d\'œuvre
            'Tuyauteur',
            'Soudeur TIG',
            'Soudeur électrode',
            'Chef de chantier',
            'Contrôleur qualité',
            'Levageur',

            // Divers
            'Piquage sur existant',
            'Modification de tracé',
            'By-pass temporaire',
            'Purge et nettoyage',
            'Traçage et marquage',
            'Dossier technique',
            'Plan de récolement',
        ];
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Societe;
use App\Models\SocieteContact;
use App\Models\DevisTuyauterie;
use App\Models\Matiere;

class DevisTuyauterieForm extends Component
{
    public ?DevisTuyauterie $devis = null;

    // 1. Informations En-tête
    public $affaire_id; // Obligatoire
    public $reference_projet;
    public $lieu_intervention;
    public $societe_id;
    public $societe_contact_id;
    public $client_nom;
    public $client_contact;
    public $client_adresse;
    public $date_emission;
    public $duree_validite = 30; // 30 jours par défaut (acier volatile)

    public $contacts = [];

    // 2. Corps du Devis (Sections et Lignes)
    public $sections = [
        [
            'titre' => 'Lot 1 : Préfabrication Atelier',
            'lignes' => [
                [
                    'type' => 'fourniture',
                    'designation' => '',
                    'matiere' => '',
                    'quantite' => 1,
                    'unite' => 'u',
                    'prix_achat' => 0, // Pour calcul marge
                    'prix_unitaire' => 0,
                    'total_ht' => 0
                ]
            ]
        ]
    ];

    // 3. Options Métier
    public $options = [
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
    ];

    // 4. Pied de page
    public $conditions_paiement = "30% à la commande, solde à réception.";
    public $delais_execution = "À définir selon planning.";

    // Totaux calculés
    public $total_ht = 0;
    public $total_tva = 0;
    public $total_ttc = 0;
    public $marge_globale = 0;
    public $marge_pourcent = 0;

    public function mount(DevisTuyauterie $devis = null, $affaire_id = null)
    {
        if ($devis && $devis->exists) {
            $this->devis = $devis;

            // Chargement des données entête
            $this->affaire_id = $devis->affaire_id;
            $this->reference_projet = $devis->reference_projet;
            $this->lieu_intervention = $devis->lieu_intervention;
            $this->societe_id = $devis->societe_id;
            $this->societe_contact_id = $devis->societe_contact_id;
            $this->client_nom = $devis->client_nom;
            $this->client_contact = $devis->client_contact;
            $this->client_adresse = $devis->client_adresse;
            $this->date_emission = $devis->date_emission ? $devis->date_emission->format('Y-m-d') : now()->format('Y-m-d');
            $this->duree_validite = $devis->duree_validite;
            $this->conditions_paiement = $devis->conditions_paiement;
            $this->delais_execution = $devis->delais_execution;

            // Options : fusion des par défaut avec les existantes
            if (is_array($devis->options)) {
                $this->options = array_merge($this->options, $devis->options);
            }

            // Chargement Sections et Lignes
            $this->sections = [];
            foreach ($devis->sections as $section) {
                $lignes = [];
                foreach ($section->lignes as $ligne) {
                    $lignes[] = [
                        // 'id' => $ligne->id, // Pas nécessaire de garder l'ID si on delete/recreate, sauf si besoin spécifique
                        'type' => $ligne->type,
                        'designation' => $ligne->designation,
                        'matiere' => $ligne->matiere,
                        'matiere_id' => $ligne->matiere_id,
                        'quantite_matiere_unitaire' => $ligne->quantite_matiere_unitaire + 0,
                        'unite_matiere' => $ligne->unite_matiere,
                        'quantite' => $ligne->quantite + 0, // Force number
                        'unite' => $ligne->unite,
                        'prix_achat' => $ligne->prix_achat + 0,
                        'prix_unitaire' => $ligne->prix_unitaire + 0,
                        'total_ht' => $ligne->total_ht + 0,
                    ];
                }
                $this->sections[] = [
                    'titre' => $section->titre,
                    'lignes' => $lignes
                ];
            }

            // Charger les contacts
            if ($this->societe_id) {
                $societe = Societe::find($this->societe_id);
                if ($societe) {
                    $this->contacts = $societe->societeContacts()->get()->toArray();
                }
            }

        } else {
            // Nouveau devis : initialiser avec affaire_id si passé en paramètre
            $this->date_emission = now()->format('Y-m-d');
            if ($affaire_id) {
                $this->affaire_id = $affaire_id;
            }
        }

        $this->calculateTotals();
    }

    // Gestion des Sections
    public function addSection()
    {
        $this->sections[] = [
            'titre' => 'Nouveau Lot',
            'lignes' => [
                [
                    'type' => 'fourniture',
                    'designation' => '',
                    'matiere' => '',
                    'matiere_id' => null,
                    'quantite_matiere_unitaire' => 0,
                    'unite_matiere' => 'ml',
                    'quantite' => 1,
                    'unite' => 'u',
                    'prix_achat' => 0,
                    'prix_unitaire' => 0,
                    'total_ht' => 0
                ]
            ]
        ];
    }

    public function removeSection($index)
    {
        unset($this->sections[$index]);
        $this->sections = array_values($this->sections); // Réindexation
        $this->calculateTotals();
    }

    // Gestion des Lignes
    public function addLine($sectionIndex)
    {
        $this->sections[$sectionIndex]['lignes'][] = [
            'type' => 'fourniture',
            'designation' => '',
            'matiere' => '',
            'matiere_id' => null,
            'quantite_matiere_unitaire' => 0,
            'unite_matiere' => 'ml',
            'quantite' => 1,
            'unite' => 'u',
            'prix_achat' => 0,
            'prix_unitaire' => 0,
            'total_ht' => 0
        ];
    }

    public function removeLine($sectionIndex, $lineIndex)
    {
        unset($this->sections[$sectionIndex]['lignes'][$lineIndex]);
        $this->sections[$sectionIndex]['lignes'] = array_values($this->sections[$sectionIndex]['lignes']);
        $this->calculateTotals();
    }

    // Sélectionner une matière depuis la base de données
    public function selectMatiere($sectionIndex, $lineIndex, $matiereId)
    {
        $matiere = Matiere::with(['standardVersion', 'material', 'unite'])->find($matiereId);

        if ($matiere) {
            // Ne touche PAS à la désignation - elle reste indépendante
            // La désignation = ce que vous fournissez au client
            // Le champ matière = les matériaux utilisés

            $this->sections[$sectionIndex]['lignes'][$lineIndex]['matiere'] = $matiere->material->nom ?? '';
            $this->sections[$sectionIndex]['lignes'][$lineIndex]['matiere_id'] = $matiere->id;

            // Ajouter la norme/version si disponible
            if ($matiere->standardVersion) {
                $this->sections[$sectionIndex]['lignes'][$lineIndex]['matiere'] .= ' ' . $matiere->standardVersion->version;
            }

            $this->calculateTotals();
        }
    }

    // Hook automatique lors de la mise à jour des champs
    public function updated($propertyName)
    {
        $this->calculateTotals();
    }

    public function updatedSocieteId($val)
    {
        if ($val) {
            $societe = Societe::find($val);
            if ($societe) {
                $this->client_nom = $societe->raison_sociale;

                $etablissement = $societe->etablissements()->first();
                if ($etablissement) {
                     $this->client_adresse = $etablissement->adresse . ($etablissement->code_postal ? "\n" . $etablissement->code_postal : "") . ($etablissement->ville ? " " . $etablissement->ville : "");
                } else {
                     $this->client_adresse = '';
                }

                $this->contacts = $societe->societeContacts()->get()->toArray();
            }
        } else {
            $this->contacts = [];
            $this->societe_contact_id = null;
        }
    }

    public function updatedSocieteContactId($val)
    {
        if ($val) {
            $contact = SocieteContact::find($val);
            if ($contact) {
                $this->client_contact = $contact->nom;
            }
        }
    }

    public function calculateTotals()
    {
        $totalHt = 0;
        $totalCout = 0;

        foreach ($this->sections as $sKey => $section) {
            foreach ($section['lignes'] as $lKey => $ligne) {
                // Calcul ligne
                $lineTotal = (float)$ligne['quantite'] * (float)$ligne['prix_unitaire'];
                $lineCost = (float)$ligne['quantite'] * (float)$ligne['prix_achat'];

                $this->sections[$sKey]['lignes'][$lKey]['total_ht'] = $lineTotal;

                $totalHt += $lineTotal;
                $totalCout += $lineCost;
            }
        }

        // Ajout forfaits
        $totalHt += (float)$this->options['frais_consommables_forfait'];

        $this->total_ht = $totalHt;
        $this->total_tva = $totalHt * 0.20; // 20% par défaut
        $this->total_ttc = $this->total_ht + $this->total_tva;

        // Calcul Marge
        $this->marge_globale = $totalHt - $totalCout;
        $this->marge_pourcent = $totalHt > 0 ? ($this->marge_globale / $totalHt) * 100 : 0;
    }

    public function save()
    {
        $this->validate([
            'reference_projet' => 'required|string|max:255',
            'client_nom' => 'required|string|max:255',
            'date_emission' => 'required|date',
            'sections' => 'required|array|min:1',
            'sections.*.titre' => 'required|string',
            'sections.*.lignes.*.designation' => 'required|string',
            'sections.*.lignes.*.quantite' => 'required|numeric|min:0.01',
            'sections.*.lignes.*.prix_unitaire' => 'required|numeric|min:0',
            // 'affaire_id' => 'required|exists:affaires,id', // TEMPORAIRE: Décommenter après ajout de la colonne en BDD
        ]);

        // Transaction DB pour intégrité
        \Illuminate\Support\Facades\DB::transaction(function () {

            $data = [
                'affaire_id' => $this->affaire_id ?: null, // Temporaire: peut être null
                'reference_projet' => $this->reference_projet,
                'lieu_intervention' => $this->lieu_intervention,
                'societe_id' => $this->societe_id ?: null,
                'societe_contact_id' => $this->societe_contact_id ?: null,
                'client_nom' => $this->client_nom,
                'client_contact' => $this->client_contact,
                'client_adresse' => $this->client_adresse,
                'date_emission' => $this->date_emission,
                'duree_validite' => $this->duree_validite,
                'conditions_paiement' => $this->conditions_paiement,
                'delais_execution' => $this->delais_execution,
                'options' => $this->options, // Casté en JSON auto par le modèle
                'total_ht' => $this->total_ht,
                'total_tva' => $this->total_tva,
                'total_ttc' => $this->total_ttc,
                'marge_globale' => $this->marge_globale,
            ];

            if ($this->devis && $this->devis->exists) {
                // Update
                $this->devis->update($data);
                $devis = $this->devis;

                // Suppression propre des anciennes sections (et lignes via cascade si config DB ou manuel)
                foreach ($devis->sections as $oldSec) {
                    $oldSec->lignes()->delete();
                    $oldSec->delete();
                }

                $message = 'Devis mis à jour avec succès !';
            } else {
                // Create
                $devis = \App\Models\DevisTuyauterie::create($data);
                $message = 'Devis créé avec succès !';
            }

            // Création Sections et Lignes (commune Create/Update)
            foreach ($this->sections as $sectionIndex => $sectionData) {
                $section = $devis->sections()->create([
                    'titre' => $sectionData['titre'],
                    'ordre' => $sectionIndex,
                ]);

                foreach ($sectionData['lignes'] as $ligneIndex => $ligneData) {
                    $section->lignes()->create([
                        'type' => $ligneData['type'],
                        'designation' => $ligneData['designation'],
                        'matiere' => $ligneData['matiere'],
                        'matiere_id' => $ligneData['matiere_id'] ?? null,
                        'quantite_matiere_unitaire' => $ligneData['quantite_matiere_unitaire'] ?? null,
                        'unite_matiere' => $ligneData['unite_matiere'] ?? null,
                        'quantite' => $ligneData['quantite'],
                        'unite' => $ligneData['unite'],
                        'prix_achat' => $ligneData['prix_achat'],
                        'prix_unitaire' => $ligneData['prix_unitaire'],
                        'total_ht' => $ligneData['total_ht'],
                        'ordre' => $ligneIndex,
                    ]);
                }
            }

            // Mise à jour de l'instance pour éviter bugs si on reste sur la page (mais on redirect)
            if (!$this->devis) {
                 $this->devis = $devis;
            }
        });

        // Redirection avec message succès
        session()->flash('success', $this->devis->exists ? 'Devis mis à jour !' : 'Devis créé !');
        return redirect()->route('devis_tuyauterie.index');
    }

    /**
     * Liste des désignations standards pour entreprise de tuyauterie
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

    public function render()
    {
        // Récupérer les matières les plus utilisées
        $matieres = Matiere::with(['material', 'standardVersion', 'unite'])
            ->orderBy('designation')
            ->limit(200)
            ->get();

        return view('livewire.devis-tuyauterie-form', [
            'societes' => Societe::orderBy('raison_sociale')->get(),
            'affaires' => \App\Models\Affaire::whereIn('statut', ['en_attente', 'en_cours'])->orderBy('code', 'desc')->get(),
            'matieres' => $matieres,
            'designations_standards' => $this->getDesignationsStandards()
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Societe;
use App\Models\SocieteMatiere;
use App\Models\SocieteMatierePrix;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\View\View;
use Log;

class MatierePrixController extends Controller
{
    /**
     * Affiche l'historique des prix pour une matière et un fournisseur avec filtres
     */
    public function show(Request $request, Matiere $matiere, Societe $fournisseur): View
    {
        // Vérifier que la société est bien un fournisseur
        if (!in_array($fournisseur->societe_type_id, ['2', '3'])) {
            abort(404, 'Société non trouvée ou n\'est pas un fournisseur');
        }

        // Récupérer tous les prix pour cette matière et ce fournisseur
        $fournisseurs_prix = $matiere->prixPourSociete($fournisseur->id)
            ->orderBy('date', 'asc')
            ->get();

        // Appliquer les filtres de période
        $fournisseurs_prix_filtered = $this->applyDateFilters($fournisseurs_prix, $request);

        // Préparer les données pour le graphique (données filtrées)
        $dates_filtered = $fournisseurs_prix_filtered->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d H:i:s');
        })->toArray();

        $prix_filtered = $fournisseurs_prix_filtered->pluck('prix_unitaire')->toArray();

        // Données non filtrées pour les modals (garder la compatibilité)
        $dates = $fournisseurs_prix->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d H:i:s');
        })->toArray();

        $prix = $fournisseurs_prix->pluck('prix_unitaire')->toArray();

        return view('matieres.show_prix', compact(
            'matiere',
            'fournisseur',
            'fournisseurs_prix',
            'fournisseurs_prix_filtered',
            'dates',
            'prix',
            'dates_filtered',
            'prix_filtered'
        ));
    }

    /**
     * Appliquer les filtres de dates selon la période sélectionnée
     */
    private function applyDateFilters($collection, Request $request)
    {
        $periode = $request->get('periode');
        $dateDebut = $request->get('date_debut');
        $dateFin = $request->get('date_fin');

        if (!$periode) {
            return $collection; // Retourner toutes les données si aucune période n'est sélectionnée
        }

        $now = Carbon::now();
        $startDate = null;
        $endDate = null;

        switch ($periode) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;

            case 'week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                break;

            case 'month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;

            case '3months':
                $startDate = $now->copy()->subMonths(3)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;

            case '6months':
                $startDate = $now->copy()->subMonths(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;

            case 'year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                break;

            case 'custom':
                if ($dateDebut) {
                    $startDate = Carbon::parse($dateDebut)->startOfDay();
                }
                if ($dateFin) {
                    $endDate = Carbon::parse($dateFin)->endOfDay();
                }
                break;
        }

        // Filtrer la collection selon les dates
        return $collection->filter(function ($item) use ($startDate, $endDate) {
            $itemDate = Carbon::parse($item->date);
            
            if ($startDate && $endDate) {
                return $itemDate->between($startDate, $endDate);
            } elseif ($startDate) {
                return $itemDate->gte($startDate);
            } elseif ($endDate) {
                return $itemDate->lte($endDate);
            }
            
            return true;
        });
    }

    /**
     * Ajouter un nouveau prix
     */
    public function store(Request $request, Matiere $matiere, Societe $fournisseur)
    {
        // Validation des données
        $request->validate([
            'prix_unitaire' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        try {
            // Vérifier que la société est bien un fournisseur
            if (!in_array($fournisseur->societe_type_id, ['2', '3'])) {
                return redirect()
                    ->back()
                    ->with('error', 'Cette société n\'est pas un fournisseur.');
            }

            // Vérifier si la relation société-matière existe
            $societeMatiere = SocieteMatiere::where('societe_id', $fournisseur->id)
                ->where('matiere_id', $matiere->id)
                ->first();

            if (!$societeMatiere) {
                return redirect()
                    ->back()
                    ->with('error', 'Ce fournisseur n\'est pas associé à cette matière.');
            }

            // Créer le prix via SocieteMatierePrix
            SocieteMatierePrix::create([
                'societe_matiere_id' => $societeMatiere->id,
                'prix_unitaire' => $request->prix_unitaire,
                'date' => $request->date,
                'description' => 'Prix ajouté manuellement',
            ]);

            return redirect()
                ->route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id])
                ->with('success', 'Prix ajouté avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du prix', [
                'matiere_id' => $matiere->id,
                'societe_id' => $fournisseur->id,
                'prix_unitaire' => $request->prix_unitaire,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout du prix.');
        }
    }

    /**
     * Modifier un prix existant
     */
    public function update(Request $request, Matiere $matiere, Societe $fournisseur, $prix_id)
    {
        // Validation des données
        $request->validate([
            'prix_unitaire' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        try {
            // Vérifier que la société est bien un fournisseur
            if (!in_array($fournisseur->societe_type_id, ['2', '3'])) {
                return redirect()
                    ->back()
                    ->with('error', 'Cette société n\'est pas un fournisseur.');
            }

            // Récupérer le prix
            $prix = SocieteMatierePrix::findOrFail($prix_id);

            // Vérifier que le prix appartient bien à cette relation matière-société
            $societeMatiere = SocieteMatiere::where('societe_id', $fournisseur->id)
                ->where('matiere_id', $matiere->id)
                ->first();

            if (!$societeMatiere || $prix->societe_matiere_id != $societeMatiere->id) {
                return redirect()
                    ->back()
                    ->with('error', 'Ce prix n\'appartient pas à cette relation matière-fournisseur.');
            }

            // Mettre à jour le prix
            $prix->update([
                'prix_unitaire' => $request->prix_unitaire,
                'date' => $request->date,
                'description' => $prix->description ?: 'Prix modifié',
            ]);

            return redirect()
                ->route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id])
                ->with('success', 'Prix modifié avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la modification du prix', [
                'matiere_id' => $matiere->id,
                'societe_id' => $fournisseur->id,
                'prix_id' => $prix_id,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification du prix.');
        }
    }

    /**
     * Supprimer un prix
     */
    public function delete(Matiere $matiere, Societe $fournisseur, $prix_id)
    {
        try {
            // Vérifier que la société est bien un fournisseur
            if (!in_array($fournisseur->societe_type_id, ['2', '3'])) {
                return redirect()
                    ->back()
                    ->with('error', 'Cette société n\'est pas un fournisseur.');
            }

            // Récupérer le prix
            $prix = SocieteMatierePrix::findOrFail($prix_id);

            // Vérifier que le prix appartient bien à cette relation matière-société
            $societeMatiere = SocieteMatiere::where('societe_id', $fournisseur->id)
                ->where('matiere_id', $matiere->id)
                ->first();

            if (!$societeMatiere || $prix->societe_matiere_id != $societeMatiere->id) {
                return redirect()
                    ->back()
                    ->with('error', 'Ce prix n\'appartient pas à cette relation matière-fournisseur.');
            }

            // Supprimer le prix
            $prix->delete();

            return redirect()
                ->route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id])
                ->with('success', 'Prix supprimé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du prix', [
                'matiere_id' => $matiere->id,
                'societe_id' => $fournisseur->id,
                'prix_id' => $prix_id,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la suppression du prix.');
        }
    }
}

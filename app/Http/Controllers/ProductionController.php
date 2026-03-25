<?php

namespace App\Http\Controllers;

use App\Models\Affaire;
use App\Models\Materiel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ProductionController extends Controller
{
    public function index(Request $request): View
    {
        $affaires = Affaire::orderByRaw("CASE WHEN statut = 'termine' THEN 1 ELSE 0 END")
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('production.index', compact('affaires'));
    }

    public function show(Affaire $affaire): View
    {
        $affaire->load(['cdes', 'ddps', 'materiels', 'reparations']);
        $availableMateriels = Materiel::where('status', 'actif')->get();
        $statuts = Affaire::getStatuts();
        return view('production.show', compact('affaire', 'availableMateriels', 'statuts'));
    }

    public function updateStatus(Request $request, Affaire $affaire)
    {
        if ($affaire->statut === Affaire::STATUT_TERMINE) {
            return redirect()->back()->with('error', 'Cette affaire est terminée et ne peut plus être modifiée.');
        }

        $request->validate([
            'statut' => 'required|in:' . implode(',', array_keys(Affaire::getStatuts())),
        ]);

        if ($affaire->statut === Affaire::STATUT_EN_ATTENTE && $request->statut === Affaire::STATUT_TERMINE) {
            return redirect()->back()->with('error', 'Impossible de passer directement de "En attente" à "Terminé".');
        }

        $affaire->update(['statut' => $request->statut]);

        return redirect()->route('production.show', $affaire)->with('success', 'Statut mis à jour avec succès.');
    }

    public function assignMateriel(Request $request, Affaire $affaire)
    {
        if ($affaire->statut === Affaire::STATUT_TERMINE) {
            return redirect()->back()->with('error', 'Cette affaire est terminée et ne peut plus être modifiée.');
        }

        $rules = [
            'materiel_id' => 'required|exists:materiels,id',
            'date_debut' => 'required|date',
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
        ];

        // Ajouter la validation de la date d'échéance si elle existe
        if ($affaire->date_fin_prevue) {
            $rules['date_fin'][] = 'before_or_equal:' . $affaire->date_fin_prevue;
        }

        $messages = [
            'date_fin.before_or_equal' => 'La date de fin de l\'assignation ne peut pas dépasser la date d\'échéance de l\'affaire (' . ($affaire->date_fin_prevue ? Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') : '') . ').',
        ];

        $request->validate($rules, $messages);

        $affaire->materiels()->attach($request->materiel_id, [
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'statut' => 'reserve',
        ]);

        return redirect()->route('production.show', $affaire)->with('success', 'Matériel assigné avec succès.');
    }

    public function detachMateriel(Request $request, Affaire $affaire, Materiel $materiel)
    {
        if ($affaire->statut === Affaire::STATUT_TERMINE) {
            return redirect()->back()->with('error', 'Cette affaire est terminée et ne peut plus être modifiée.');
        }

        // Mettre à jour la date de fin à maintenant au lieu de détacher complètement
        // pour garder l'historique
        $affaire->materiels()->updateExistingPivot($materiel->id, [
            'date_fin' => now(),
            'statut' => 'termine'
        ]);

        return redirect()->route('production.show', $affaire)->with('success', 'Matériel désassigné avec succès.');
    }

    public function indexColProductionSmall()
    {
        return $this->indexColProduction(true);
    }

    public function indexColProduction($isSmall = false)
    {
        $limit = $isSmall ? 5 : 30;
        $affaires = Affaire::orderByRaw("CASE WHEN statut = 'termine' THEN 1 ELSE 0 END")
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        return view('production.index_col', compact('affaires', 'isSmall'));
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\View\View;

use Illuminate\Http\Request;
use App\Models\Materiel;
use App\Models\Reparation;
use App\Models\Facture;
use Illuminate\Support\Facades\Auth;

class ReparationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');

        // Charger les réparations actives (non-archivées)
        $activeQuery = Reparation::with(['materiel', 'user'])
            ->whereNotIn('status', ['archived', 'closed']);

        if ($search) {
            $activeQuery->where(function($q) use ($search) {
                $q->where('id', 'ilike', '%' . $search . '%')
                  ->orWhere('description', 'ilike', '%' . $search . '%')
                  ->orWhereHas('materiel', function($q) use ($search) {
                      $q->where('reference', 'ilike', '%' . $search . '%');
                  });
            });
        }

        // Charger les réparations archivées
        $archivedQuery = Reparation::with(['materiel', 'user'])
            ->whereIn('status', ['archived', 'closed']);

        if ($search) {
            $archivedQuery->where(function($q) use ($search) {
                $q->where('id', 'ilike', '%' . $search . '%')
                  ->orWhere('description', 'ilike', '%' . $search . '%')
                  ->orWhereHas('materiel', function($q) use ($search) {
                      $q->where('reference', 'ilike', '%' . $search . '%');
                  });
            });
        }

        // Appliquer le tri
        if ($sortBy === 'status') {
            $activeReparations = $activeQuery->orderBy('status', $sortOrder)->get();
            $archivedReparations = $archivedQuery->orderBy('status', $sortOrder)->get();
        } else {
            $activeReparations = $activeQuery->latest()->get();
            $archivedReparations = $archivedQuery->latest()->get();
        }

        return view('reparation.index', compact('activeReparations', 'archivedReparations'));
    }


    public function create()
    {
        $materiels = Materiel::all();
        return view('reparation.create', compact('materiels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'materiel_id' => 'required|exists:materiels,id',
            'description' => 'required|string',
        ]);

        // Chercher si le matériel est assigné à une affaire active
        $materiel = Materiel::find($request->input('materiel_id'));
        $affaireId = null;
        if ($materiel) {
            $activeAffaire = $materiel->affaires()
                ->wherePivot('date_debut', '<=', now())
                ->where(function ($query) {
                    $query->where('affaire_materiel.date_fin', '>=', now())
                          ->orWhereNull('affaire_materiel.date_fin');
                })
                ->where('affaires.statut', '!=', \App\Models\Affaire::STATUT_TERMINE)
                ->first();

            if ($activeAffaire) {
                $affaireId = $activeAffaire->id;
            }
        }

        // Créer la demande de réparation
        $reparation = Reparation::create([
            'user_id' => Auth::id(),
            'materiel_id' => $request->input('materiel_id'),
            'affaire_id' => $affaireId,
            'description' => $request->input('description'),
            'status' => 'pending',
        ]);

        // Créer automatiquement une facture associée
        $numeroFacture = 'FAC-' . now()->format('Ymd') . '-' . str_pad(
            Facture::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        Facture::create([
            'numero_facture' => $numeroFacture,
            'date_emission' => now()->toDateString(),
            'montant_total' => 0.00,
            'reparation_id' => $reparation->id,
        ]);

        // Mettre le matériel en inactif
        $materiel = Materiel::find($request->input('materiel_id'));
        if ($materiel) {
            $materiel->status = 'inactif';
            $materiel->save();
        }

        return redirect()->route('reparation.index')->with('success', 'Demande de réparation et facture créées avec succès.');
    }

    /**
     * Affiche le détail d'une réparation
     */
    public function show(Reparation $reparation)
    {
        $reparation->load(['materiel', 'user']);
        return view('reparation.show', compact('reparation'));
    }

    /**
     * Affiche le formulaire d'édition pour une réparation
     */
    public function edit(Reparation $reparation)
    {
        // Bloquer la modification des réparations archivées
        if ($reparation->status === 'archived' || $reparation->status === 'closed') {
            abort(403, 'Les réparations archivées ne peuvent pas être modifiées.');
        }

        // Autorisation: le demandeur peut modifier sa demande, ou un utilisateur ayant la permission
        if (Auth::id() !== $reparation->user_id && !Auth::user()->hasPermission('gerer_les_reparations')) {
            abort(403);
        }

        return view('reparation.edit', compact('reparation'));
    }

    /**
     * Met à jour la réparation
     */
    public function update(Request $request, Reparation $reparation)
    {
        // Bloquer la modification des réparations archivées
        if ($reparation->status === 'archived' || $reparation->status === 'closed') {
            abort(403, 'Les réparations archivées ne peuvent pas être modifiées.');
        }

        if (Auth::id() !== $reparation->user_id && !Auth::user()->hasPermission('gerer_les_reparations')) {
            abort(403);
        }

        $data = $request->validate([
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed,closed',
        ]);

        $reparation->update($data);

        return redirect()->route('reparation.show', $reparation->id)->with('success', 'Réparation mise à jour.');
    }

    /**
     * Met à jour uniquement le statut d'une réparation (depuis la page show)
     */
    public function updateStatus(Request $request, Reparation $reparation)
    {
        // Bloquer le changement de statut des réparations archivées
        if ($reparation->status === 'archived' || $reparation->status === 'closed') {
            abort(403, 'Les réparations archivées ne peuvent pas être modifiées.');
        }

        if (Auth::id() !== $reparation->user_id && !Auth::user()->hasPermission('gerer_les_reparations')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,closed',
        ]);

        $newStatus = $request->input('status');

        // Si "completed", archiver automatiquement
        if ($newStatus === 'completed') {
            $reparation->status = 'archived';
            $reparation->save();
            $this->reconcileMaterielStatus($reparation->materiel_id);
            return redirect()->route('reparation.show', $reparation->id)->with('success', 'Réparation archivée automatiquement.');
        }

        $reparation->status = $newStatus;
        $reparation->save();

        return redirect()->route('reparation.show', $reparation->id)->with('success', 'Statut mis à jour.');
    }

    /**
     * Archive manuellement une réparation
     */
    public function archive(Request $request, Reparation $reparation)
    {
        if (Auth::id() !== $reparation->user_id && !Auth::user()->hasPermission('gerer_les_reparations')) {
            abort(403);
        }

        $reparation->status = 'archived';
        $reparation->save();

        $this->reconcileMaterielStatus($reparation->materiel_id);

        return redirect()->route('reparation.show', $reparation->id)->with('success', 'Réparation archivée.');
    }

    /**
     * Vérifie les réparations d'un matériel :
     * - si des réparations actives existent => matériel reste inactif
     * - sinon matériel redevient actif
     */
    protected function reconcileMaterielStatus($materielId)
    {
        $materiel = Materiel::find($materielId);
        if (!$materiel) {
            return;
        }

        $hasOpen = Reparation::where('materiel_id', $materielId)
            ->whereNotIn('status', ['archived', 'closed'])
            ->exists();

        $materiel->status = $hasOpen ? 'inactif' : 'actif';
        $materiel->save();
    }
}

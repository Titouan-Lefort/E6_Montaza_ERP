<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use FFI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $query = Facture::with('reparation.materiel');

        // Filtre par recherche (numéro facture, numéro réparation, référence matériel)
        if ($search = $request->get('search')) {
            $query->where('numero_facture', 'ilike', '%' . $search . '%')
                  ->orWhere('reparation_id', 'ilike', '%' . $search . '%')
                  ->orWhereHas('reparation.materiel', function($q) use ($search) {
                      $q->where('reference', 'ilike', '%' . $search . '%');
                  });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');

        match($sortBy) {
            'numero' => $query->orderBy('numero_facture', $sortOrder),
            'montant' => $query->orderBy('montant_total', $sortOrder),
            'date' => $query->orderBy('date_emission', $sortOrder),
            default => $query->orderBy('date_emission', 'desc'),
        };

        $factures = $query->get();
        return view('reparation.facture.index', compact('factures'));
    }

    public function create()
    {
        // Permission: gestion des factures requise
        if (!Auth::user() || !Auth::user()->hasPermission('gerer_les_factures_reparations')) {
            abort(403);
        }

        // Fournir la liste des réparations actives (exclure closed et archived)
        $reparations = \App\Models\Reparation::with('materiel')
            ->whereNotIn('status', ['closed', 'archived'])
            ->latest()
            ->get();
        return view('reparation.facture.create', compact('reparations'));
    }

    public function store(Request $request)
    {
        if (!Auth::user() || !Auth::user()->hasPermission('gerer_les_factures_reparations')) {
            abort(403);
        }
        // Validation des données
        $validatedData = $request->validate([
            'numero_facture' => 'required|unique:factures,numero_facture',
            'date_emission' => 'required|date',
            'montant_total' => 'required|numeric',
            'reparation_id' => 'required|exists:reparations,id',
        ]);

        $reparation = \App\Models\Reparation::findOrFail($request->reparation_id);
        if ($reparation->affaire && ($reparation->affaire->statut === \App\Models\Affaire::STATUT_TERMINE || $reparation->affaire->statut === \App\Models\Affaire::STATUT_ARCHIVE)) {
             return redirect()->back()->withErrors(['reparation_id' => 'Impossible de lier une facture à une réparation d\'une affaire terminée ou archivée.']);
        }

        // Création de la facture
        \App\Models\Facture::create($validatedData);

        return redirect()->route('reparation.facture.index')->with('success', 'Facture créée avec succès.');
    }

    public function show(Facture $facture)
    {
        $facture->load('reparation.materiel');
        return view('reparation.facture.show', compact('facture'));
    }

    public function edit(Facture $facture)
    {
        if (!Auth::user() || !Auth::user()->hasPermission('gerer_les_factures_reparations')) {
            abort(403);
        }

        $reparations = \App\Models\Reparation::with('materiel')
            ->whereNotIn('status', ['closed', 'archived'])
            ->latest()
            ->get();
        return view('reparation.facture.edit', compact('facture', 'reparations'));
    }

    public function update(Request $request, Facture $facture)
    {
        if (!Auth::user() || !Auth::user()->hasPermission('gerer_les_factures_reparations')) {
            abort(403);
        }

        if ($facture->reparation && $facture->reparation->affaire && ($facture->reparation->affaire->statut === \App\Models\Affaire::STATUT_TERMINE || $facture->reparation->affaire->statut === \App\Models\Affaire::STATUT_ARCHIVE)) {
             abort(403, 'Impossible de modifier une facture liée à une affaire terminée ou archivée.');
        }

        $validatedData = $request->validate([
            'numero_facture' => 'required|unique:factures,numero_facture,' . $facture->id,
            'date_emission' => 'required|date',
            'montant_total' => 'required|numeric',
            'reparation_id' => 'required|exists:reparations,id',
        ]);

        $facture->update($validatedData);

        return redirect()->route('reparation.facture.show', $facture->id)->with('success', 'Facture mise à jour avec succès.');
    }

    public function destroy(Facture $facture)
    {
        if (!Auth::user() || !Auth::user()->hasPermission('gerer_les_factures_reparations')) {
            abort(403);
        }

        $facture->delete();
        return redirect()->route('reparation.facture.index')->with('success', 'Facture supprimée avec succès.');
    }
}

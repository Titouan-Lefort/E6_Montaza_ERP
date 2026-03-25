<?php

namespace App\Http\Controllers;

use App\Models\Cde;
use App\Models\CdeSocieteContact;
use App\Models\Ddp;
use App\Models\DdpLigneFournisseur;
use App\Models\Etablissement;
use App\Models\Societe;
use App\Models\SocieteContact;
use Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Contracts\View\View;

class SocieteContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'societe_id' => 'nullable',
            'etablissement_id' => 'required|integer|exists:etablissements,id',
            'nom' => 'required|string',
            'email' => 'required|email',
            'telephone_portable' => 'nullable|string',
            'telephone_fixe' => 'nullable|string',
            'fonction' => 'nullable|string',
        ]);
        Cache::flush();

        $contact = SocieteContact::create(
            [
                'etablissement_id' => $request->etablissement_id,
                'nom' => $request->nom,
                'email' => $request->email,
                'telephone_portable' => $request->telephone_portable,
                'telephone_fixe' => $request->telephone_fixe,
                'fonction' => $request->fonction,
            ]
        );
        return response()->json(['success' => true, 'contact' => $contact]);
    }

    public function quickCreate(): View
    {
        $societes = Societe::select('id', 'raison_sociale')->get();
        return view('societes.contacts.quick-create', compact('societes'));
    }
    public function showJson($societe_id,  $etablissement_id): JsonResponse
    {
        $etablissement = Etablissement::findOrFail($etablissement_id);
        $contacts = $etablissement->contacts;
        return response()->json($contacts);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocieteContact $contact): JsonResponse
    {
        // Vérifier si le contact est utilisé ailleurs (par exemple dans des missions, devis, etc.)
        if ($this->isContactUsed($contact)) {
            return response()->json([
                'success' => false,
                'message' => 'Ce contact ne peut pas être supprimé car il est utilisé dans d\'autres enregistrements.'
            ], 422);
        }

        try {
            $contact->delete();
            Cache::flush();

            return response()->json([
                'success' => true,
                'message' => 'Contact supprimé avec succès.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du contact.'
            ], 500);
        }
    }

    /**
     * Check if contact is used in other records
     */
    private function isContactUsed(SocieteContact $contact): bool
    {
        return
            // Vérifie si utilisé dans une Ddp
            DdpLigneFournisseur::where('societe_contact_id', $contact->id)->exists() ||
            CdeSocieteContact::where('societe_contact_id', $contact->id)->exists();
    }
}

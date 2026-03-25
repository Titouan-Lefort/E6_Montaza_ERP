<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\PersonnelConge;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        if (!is_string($search)) {
            $search = '';
        }

        $show_deleted = $request->input('show_deleted');
        if ($show_deleted) {
            $personnels = Personnel::onlyTrashed()->get();
            return view('personnel.index', ['personnels' => $personnels]);
        }

        // Rechercher des employés en fonction du terme de recherche
        // Exclure les employés avec le statut "parti" (ils sont dans "Anciens employés")
        $personnels = Personnel::query()
            ->where('statut', '!=', 'parti')
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('nom', 'ILIKE', "%{$search}%")
                        ->orWhere('prenom', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%")
                        ->orWhere('matricule', 'ILIKE', "%{$search}%")
                        ->orWhere('poste', 'ILIKE', "%{$search}%")
                        ->orWhere('departement', 'ILIKE', "%{$search}%");
                });
            })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('personnel.index', ['personnels' => $personnels]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personnel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|string|max:255|unique:personnels,matricule',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:personnels,email',
            'telephone' => 'nullable|string|max:20',
            'telephone_mobile' => 'nullable|string|max:20',
            'poste' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
            'date_embauche' => 'nullable|date',
            'date_depart' => 'nullable|date|after_or_equal:date_embauche',
            'raison_depart' => 'nullable|in:demission,licenciement,retraite,fin_contrat,mutation,autre',
            'motif_depart' => 'nullable|string|max:1000',
            'salaire' => 'nullable|numeric|min:0',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'numero_securite_sociale' => 'nullable|string|max:50',
            'statut' => 'required|in:actif,suspendu,parti',
            'notes' => 'nullable|string',
        ]);

        $personnel = Personnel::create($validated);

        // Redirection intelligente selon le statut de départ
        if ($validated['statut'] === 'parti') {
            return redirect()->route('personnel.anciens-employes')
                ->with('success', "L'employé {$personnel->prenom} {$personnel->nom} a été créé et ajouté aux anciens employés.");
        }

        return redirect()->route('personnel.index')
            ->with('success', 'Employé créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel)
    {
        $personnel->load(['affaires', 'conges' => function($query) {
            $query->orderBy('date_debut', 'desc');
        }]);
        return view('personnel.show', compact('personnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel $personnel)
    {
        return view('personnel.edit', compact('personnel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel $personnel)
    {
        // Sauvegarder l'ancien statut pour la logique de redirection
        $ancienStatut = $personnel->statut;

        $validated = $request->validate([
            'matricule' => 'required|string|max:255|unique:personnels,matricule,' . $personnel->id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:personnels,email,' . $personnel->id,
            'telephone' => 'nullable|string|max:20',
            'telephone_mobile' => 'nullable|string|max:20',
            'poste' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
            'date_embauche' => 'nullable|date',
            'date_depart' => 'nullable|date|after_or_equal:date_embauche',
            'raison_depart' => 'nullable|in:demission,licenciement,retraite,fin_contrat,mutation,autre',
            'motif_depart' => 'nullable|string|max:1000',
            'salaire' => 'nullable|numeric|min:0',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'numero_securite_sociale' => 'nullable|string|max:50',
            'statut' => 'required|in:actif,suspendu,parti',
            'notes' => 'nullable|string',
        ]);

        $personnel->update($validated);

        // Redirection intelligente selon le changement de statut
        $nouveauStatut = $validated['statut'];

        if ($nouveauStatut === 'parti' && $ancienStatut !== 'parti') {
            // L'employé vient de passer à "parti" → rediriger vers les anciens employés
            return redirect()->route('personnel.anciens-employes')
                ->with('success', "L'employé {$personnel->prenom} {$personnel->nom} a été mis à jour et déplacé vers les anciens employés.");
        } elseif ($nouveauStatut !== 'parti' && $ancienStatut === 'parti') {
            // L'employé n'est plus "parti" → rediriger vers la liste principale
            return redirect()->route('personnel.index')
                ->with('success', "L'employé {$personnel->prenom} {$personnel->nom} a été mis à jour et réintégré dans la liste du personnel actif.");
        }

        // Sinon, retour à la liste principale
        return redirect()->route('personnel.index')
            ->with('success', 'Employé mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personnel $personnel)
    {
        $personnel->delete();

        return redirect()->route('personnel.index')
            ->with('success', 'Employé supprimé avec succès.');
    }

    /**
     * Restore the specified resource.
     */
    public function restore($id)
    {
        $personnel = Personnel::onlyTrashed()->findOrFail($id);
        $personnel->restore();

        return redirect()->route('personnel.index')
            ->with('success', 'Employé restauré avec succès.');
    }

    /**
     * Ajoute un congé à un personnel
     */
    public function storeConge(Request $request, Personnel $personnel)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type' => 'required|in:conge_paye,conge_maladie,conge_sans_solde,autre',
            'motif' => 'nullable|string|max:1000',
            'statut' => 'nullable|in:demande,valide,refuse',
        ]);

        // Vérifier qu'il n'y a pas de chevauchement avec d'autres congés
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        $hasConflict = $personnel->conges()
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->where(function($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateDebut);
                });
            })
            ->exists();

        if ($hasConflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_debut' => 'Cette période chevauche un congé déjà existant.']);
        }

        $personnel->conges()->create([
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'type' => $request->input('type'),
            'motif' => $request->input('motif'),
            'statut' => $request->input('statut', 'valide'),
        ]);

        return redirect()->back()
            ->with('success', 'Le congé a été ajouté avec succès.');
    }

    /**
     * Met à jour un congé
     */
    public function updateConge(Request $request, Personnel $personnel, $congeId)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type' => 'required|in:conge_paye,conge_maladie,conge_sans_solde,autre',
            'motif' => 'nullable|string|max:1000',
            'statut' => 'nullable|in:demande,valide,refuse',
        ]);

        $conge = $personnel->conges()->findOrFail($congeId);

        // Vérifier qu'il n'y a pas de chevauchement avec d'autres congés (sauf celui en cours)
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        $hasConflict = $personnel->conges()
            ->where('id', '!=', $congeId)
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->where(function($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateDebut);
                });
            })
            ->exists();

        if ($hasConflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_debut' => 'Cette période chevauche un congé déjà existant.']);
        }

        $conge->update([
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'type' => $request->input('type'),
            'motif' => $request->input('motif'),
            'statut' => $request->input('statut', 'valide'),
        ]);

        return redirect()->back()
            ->with('success', 'Le congé a été mis à jour avec succès.');
    }

    /**
     * Supprime un congé
     */
    public function deleteConge(Personnel $personnel, $congeId)
    {
        $conge = $personnel->conges()->findOrFail($congeId);
        $conge->delete();

        return redirect()->back()
            ->with('success', 'Le congé a été supprimé avec succès.');
    }

    /**
     * Affiche la liste des anciens employés
     */
    public function anciensEmployes(Request $request)
    {
        $search = $request->input('search');
        if (!is_string($search)) {
            $search = '';
        }

        $raison = $request->input('raison');

        // Récupérer les employés avec le statut "parti"
        $personnels = Personnel::query()
            ->where('statut', 'parti')
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('nom', 'ILIKE', "%{$search}%")
                        ->orWhere('prenom', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%")
                        ->orWhere('matricule', 'ILIKE', "%{$search}%")
                        ->orWhere('poste', 'ILIKE', "%{$search}%")
                        ->orWhere('departement', 'ILIKE', "%{$search}%");
                });
            })
            ->when($raison, function ($query, $raison) {
                $query->where('raison_depart', $raison);
            })
            ->orderBy('date_depart', 'desc')
            ->paginate(20);

        return view('personnel.anciens-employes', compact('personnels'));
    }

    /**
     * Met à jour uniquement le statut d'un personnel
     */
    public function updateStatut(Request $request, Personnel $personnel)
    {
        $validated = $request->validate([
            'statut' => 'required|in:actif,suspendu,parti',
            'date_depart' => 'nullable|date',
            'raison_depart' => 'nullable|in:demission,licenciement,retraite,fin_contrat,mutation,autre',
            'motif_depart' => 'nullable|string|max:1000',
        ]);

        // Sauvegarder l'ancien statut pour la logique de redirection
        $ancienStatut = $personnel->statut;

        // Si le statut passe à "parti", vérifier que les informations de départ sont fournies
        if ($validated['statut'] === 'parti') {
            if ($request->filled('raison_depart') && $request->input('raison_depart') === 'licenciement') {
                // Pour un licenciement, le motif est obligatoire
                if (!$request->filled('motif_depart')) {
                    return redirect()->back()
                        ->withErrors(['motif_depart' => 'Le motif est obligatoire en cas de licenciement.'])
                        ->withInput();
                }
            }
        }

        // Mettre à jour le statut et les informations de départ
        $personnel->statut = $validated['statut'];

        if ($validated['statut'] === 'parti') {
            $personnel->date_depart = $validated['date_depart'] ?? now();
            $personnel->raison_depart = $validated['raison_depart'] ?? null;
            $personnel->motif_depart = $validated['motif_depart'] ?? null;
        }

        $personnel->save();

        // Redirection intelligente selon le changement de statut
        if ($validated['statut'] === 'parti' && $ancienStatut !== 'parti') {
            // L'employé vient de passer à "parti" → rediriger vers les anciens employés
            return redirect()->route('personnel.anciens-employes')
                ->with('success', "L'employé {$personnel->prenom} {$personnel->nom} a été déplacé vers les anciens employés.");
        } elseif ($validated['statut'] !== 'parti' && $ancienStatut === 'parti') {
            // L'employé n'est plus "parti" → rediriger vers la liste principale
            return redirect()->route('personnel.index')
                ->with('success', "L'employé {$personnel->prenom} {$personnel->nom} a été réintégré dans la liste du personnel actif.");
        }

        // Sinon, retour à la page précédente
        return redirect()->back()
            ->with('success', 'Le statut a été mis à jour avec succès.');
    }
}

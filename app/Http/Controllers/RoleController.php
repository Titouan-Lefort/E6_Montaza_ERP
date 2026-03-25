<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Models\Notification;
use Auth;
use Route;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $role = 0): View
    {
        $role = Role::withTrashed()->findOrFail($role ?: 1);
        $roles = Role::withTrashed()->get();
        $entites = Entite::with(['roles' => function ($query) {
            $query->withTrashed(); // Inclure les rôles supprimés
        }])->get();
        $users = $role->users()->get();
        return view('roles.index', [
            'roles' => $roles,
            'entites' => $entites,
            'role' => $role,
            'users' => $users,
        ]);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'entite_id' => 'required|integer|exists:entites,id',
        ]);

        $existingRole = Role::where('name', $request->role_name)
            ->where('entite_id', $request->entite_id)
            ->first();

        if ($existingRole) {
            return redirect()->back()->withErrors(['role_name' => 'Un poste avec ce nom existe déjà.'])->withInput();
        }
        $new_role_name = $request->role_name;
        $new_entite_id = $request->entite_id;
        if (!is_string($new_role_name) || !is_int($new_entite_id)) {
            return redirect()->back()->withErrors(['role_name' => 'Le nom du poste doit être une chaîne de caractères et l\'entité doit être un entier.'])->withInput();
        }
        $role = new Role();
        $role->name = $new_role_name;
        $role->entite_id = $new_entite_id;
        $role->save();

        return redirect()->back()->with('status', 'Poste créé avec succès.');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Role $role)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Role $role)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'entite_id' => 'required|integer|exists:entites,id',
        ]);

        $existingRole = Role::where('name', $request->role_name)
            ->where('entite_id', $request->entite_id)
            ->where('id', '!=', $role->id)
            ->first();

        if ($existingRole) {
            return redirect()->back()->withErrors(['role_name' => 'Un poste avec ce nom existe déjà.'])->withInput();
        }
        $new_role_name = $request->role_name;
        $new_entite_id = $request->entite_id;
        if (!is_string($new_role_name) || !is_int($new_entite_id)) {
            return redirect()->back()->withErrors(['role_name' => 'Le nom du poste doit être une chaîne de caractères et l\'entité doit être un entier.'])->withInput();
        }
        $role->name = $new_role_name;
        $role->entite_id = $new_entite_id;
        $role->save();

        return redirect()->back()->with('status', 'Poste mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->undeletable) {
            return redirect()->back()->withErrors(['role' => 'Ce poste ne peut pas être désactivé.']);
        }

        $role->delete();
        $auth_user = Auth::user();
        if (!$auth_user) {
            return redirect()->back()->withErrors(['role' => 'Utilisateur non authentifié.']);
        }
        $userRole = $auth_user->getRole();
        $role_cible = Role::withTrashed()->findOrFail($userRole->id);
        $messsage = 'Le poste <strong>' . $role->name . '</strong> a été désactivé par ' . $auth_user->getName() . ', les utilisateurs affectés à ce poste ont été déconnectés :';
        foreach ($role->users as $user) {
            $messsage .= '<br/> - ' . $user->first_name . ' ' . $user->last_name;
        }
        Notification::createNotification(
            $role_cible,
            'system',
            'Poste désactivé',
            $messsage,
            'Voir le poste désactivé',
            "roles.index",
            ['role' => $role->id],
            'Voir le poste désactivé'
        );
        return redirect()->back()->with('status', 'Poste désactivé avec succès.');
    }
    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id): RedirectResponse
    {
        $role = Role::withTrashed()->findOrFail($id);
        if (!($role instanceof Role)) {
            return redirect()->back()->withErrors(['role' => 'Poste introuvable.']);
        }
        $role->restore();

        return redirect()->back()->with('status', 'Poste activé avec succès.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Entite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use function Laravel\Prompts\search;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        if (!is_string($search)) {
            $search = '';
        }

        $show_deleted = $request->input('show_deleted');
        if ($show_deleted) {
            $users = User::onlyTrashed()->get();

            return view(
                'profile.index',
                [
                    'users' => $users,
                ]
            );
        }
        // Rechercher des utilisateurs en fonction du terme de recherche (si fourni)
        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('first_name', 'ILIKE', "%{$search}%")
                    ->orWhere('last_name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('phone', 'ILIKE', "%{$search}%")
                    ->orWhereHas('role', function ($query) use ($search) {
                        $query->withTrashed()->where('name', 'ILIKE', "%{$search}%");
                    });
            })
            ->with(['role' => function ($query) {
                $query->withTrashed(); // Ensure the role relationship includes trashed roles
            }])
            ->get();

        return view(
            'profile.index',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * Display the user's profile form.
     */
    public function edit(int $id = 0): View|RedirectResponse
    {
        $user = User::findOrFail($id);

        $auth_user = Auth::user();
        if (!$auth_user) {
            return Redirect::route('login');
        }

        if ($auth_user->hasPermission('gerer_les_utilisateurs') === false && $auth_user->id !== $user->id) {
            abort(403);
        }
        $roles = Role::all();
        $entites = Entite::all();

        return view('profile.edit', [
            'user' => $user,
            'roles' => $roles,
            'entites' => $entites,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = User::findOrFail($request->id);
        $auth_user = Auth::user();
        if (!$auth_user) {
            return Redirect::route('login');
        }
        if ($auth_user->hasPermission('gerer_les_utilisateurs') === false && $auth_user->id !== $user->id) {
            abort(403);
        }
        $user->update($request->only(['first_name', 'last_name', 'phone', 'email']));

        return Redirect::route('profile.edit', ['id' => $user->id])->with('status', "Profil de {$user->first_name} {$user->last_name} modifié");
    }

    public function updateAdmin(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = User::findOrFail($request->id);
        $user->update($request->only(['role_id']));

        return Redirect::back()->with('status', 'Profil modifié');
    }

    /**
     * Summary of destroy
     */
    public function destroy(User $user): RedirectResponse
    {
        $user = User::findOrFail($user->id);
        $user->delete();

        return Redirect::route('profile.index')->with('status', "Compte {$user->first_name} {$user->last_name} désactivé");
    }

    /**
     * Summary of restore
     */
    public function restore(int $int): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($int);
        if ($user->trashed()) {
            $user->restore();

            return Redirect::route('profile.index')->with('status', "Compte {$user->first_name} {$user->last_name} restauré");
        }
        return Redirect::route('profile.index')->with('status', "Compte {$user->first_name} {$user->last_name} n'est pas désactivé");
    }
}

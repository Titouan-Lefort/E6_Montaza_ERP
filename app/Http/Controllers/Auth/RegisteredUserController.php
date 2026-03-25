<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): RedirectResponse|View
    {
        $user = Auth::user();
        if ($user === null) {
            return back()->withErrors(['current_password' => 'L’utilisateur n’est pas authentifié.']);
        }
        if ($user->hasPermission('gerer_les_utilisateurs') === false) {
            abort(403);
        }
        $roles = Role::all();
        $entites = Entite::all();

        return view('auth.register', [
            'roles' => $roles,
            'entites' => $entites,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role_id' => ['required', 'integer'],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        if (!is_string($first_name) || !is_string($last_name)) {
            return back()->withErrors(['invalid_input' => 'Le prénom ou le nom doivent être des chaînes de caractères.']);
        }
        $password = strtoupper(substr($first_name, 0, 1)).strtolower($last_name).date('Y');

        User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($password),
        ]);

        return redirect(route('profile.index', absolute: false))->with('status', 'Utilisateur créé avec succès');
    }
}

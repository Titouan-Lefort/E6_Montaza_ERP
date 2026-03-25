<?php

namespace App\Http\Controllers;

use App\Models\Entite;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelChange;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $role = 0): View
    {
        if ($role !== 0) {
            $role = Role::findOrFail($role);
        } else {
            $role = Role::findOrFail(1);
        }

        return view('permissions.index', [
            'permissions' => Permission::all(),
            'roles' => Role::all(),
            'entites' => Entite::all(),
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Permission $permission)
    // {
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): RedirectResponse
    {
        $role_id = $request->role_id;
        $role = Role::findOrFail($role_id);
        if (!($role instanceof Role)) {
            return redirect()->route('permissions.index', ['role' => $role])->with('error', 'Rôle introuvable');

        }
        $permissions_avant_get = $role->permissions()->get();
        $permissions_avant_array = $permissions_avant_get->pluck('name')->toArray();
        $permissions_avant_string = implode(',<br/>', $permissions_avant_array);
        $permissions_avant['role'] = '';
        $permissions_avant['permissions'] = $permissions_avant_string;
        $role->permissions()->detach();

        // Attach the selected permissions
        foreach ($request->all() as $key => $value) {
            if ($key !== '_token' && $key !== 'role_id' && $key !== '_method') {
                $role->permissions()->attach($value);
            }
        }
        // $permission_apres = array_merge(['role' => $role->name], $role->permissions()->get()->toArray());
        $permission_apres_get = $role->permissions()->get();
        $permission_apres_array = $permission_apres_get->pluck('name')->toArray();
        $permission_apres_string = implode(',<br/>', $permission_apres_array);
        $permission_apres['role'] = $role->name;
        $permission_apres['permissions'] = $permission_apres_string;
        $event = 'updating'; // Define the event type

        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Permissions',
            'model_id' => 0,
            'before' => $permissions_avant,
            'after' => $permission_apres,
            'event' => $event,
        ]);

        return redirect()->route('permissions.index', ['role' => $role])->with('status', 'Permissions mises à jour');
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Permission $permission)
    // {
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Permission $permission)
    // {
    // }
}

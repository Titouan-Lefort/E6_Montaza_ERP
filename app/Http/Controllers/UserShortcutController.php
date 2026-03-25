<?php

namespace App\Http\Controllers;

use App\Models\PredefinedShortcut;
use App\Models\UserShortcut;
use Auth;
use Illuminate\Http\Request;

class UserShortcutController extends Controller
{
    public function index()
    {
        // Récupérer les raccourcis dans l'ordre défini par l'utilisateur
        $userShortcuts = Auth::user()->shortcuts();
        $userShortcutIds = $userShortcuts->pluck('shortcut_id')->toArray();

        // Récupérer d'abord les raccourcis de l'utilisateur dans l'ordre
        $userOrderedShortcuts = collect();
        if (!empty($userShortcutIds)) {
            $userOrderedShortcuts = PredefinedShortcut::whereIn('id', $userShortcutIds)
                ->get()
                ->sortBy(function ($shortcut) use ($userShortcutIds) {
                    return array_search($shortcut->id, $userShortcutIds);
                });
        }

        // Puis récupérer les autres raccourcis
        $otherShortcuts = PredefinedShortcut::whereNotIn('id', $userShortcutIds)->get();

        // Combiner les deux collections
        $shortcuts = $userOrderedShortcuts->concat($otherShortcuts);

        return view('shortcuts.index', [
            'shortcuts' => $shortcuts,
            'userShortcuts' => $userShortcuts,
        ]);
    }

    public function store(Request $request)
    {
        // Remove all existing shortcuts for the user
        UserShortcut::where('user_id', Auth::id())->delete();

        // Add new shortcuts en conservant l'ordre de la liste
        $ordre = 0;
        foreach ($request->keys() as $key) {
            if (preg_match('/^is_added-([0-9]+)$/', $key)) {
                $id = explode('-', $key)[1];
                UserShortcut::create([
                    'user_id' => Auth::id(),
                    'shortcut_id' => $id,
                    'ordre' => $ordre++,
                ]);
            }
        }
        return back()->with('message', 'Raccourcis mis à jour');
    }

    public function destroy($id)
    {
        $userShortcut = UserShortcut::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $userShortcut->delete();

        return response()->json(['message' => 'Raccourci supprimé']);
    }
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:predefined_shortcuts,id',
        ]);

        // Récupérer tous les raccourcis utilisateur
        $userShortcuts = UserShortcut::where('user_id', Auth::id())->get()->keyBy('shortcut_id');

        foreach ($request->order as $index => $shortcutId) {
            // Si l'utilisateur a ce raccourci, mettre à jour son ordre
            if ($userShortcuts->has($shortcutId)) {
                $userShortcut = $userShortcuts->get($shortcutId);
                $userShortcut->ordre = $index;
                $userShortcut->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Ordre mis à jour avec succès.']);
    }
}

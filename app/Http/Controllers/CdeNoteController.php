<?php

namespace App\Http\Controllers;

use App\Models\CdeNote;
use App\Models\Entite;
use Illuminate\Http\Request;

class CdeNoteController extends Controller
{
    public function index($entite_id)
    {
        $cde_notes = CdeNote::where('entite_id', $entite_id)->get();
        $entites = Entite::all();
        $entite = Entite::findOrFail($entite_id);
        return view('administration.cde_note.index', compact('cde_notes', ['entites', 'entite']));
    }
    public function show($id)
    {
        $cde_note = CdeNote::findOrFail($id);
        $entites = Entite::all();
        $entite = Entite::findOrFail($cde_note->entite_id);
        return view('administration.cde_note.show', compact('cde_note', ['entite', 'entites']));
    }
    public function create($entite_id)
    {
        $entites = Entite::all();
        $entite = Entite::findOrFail($entite_id);
        return view('administration.cde_note.create', compact('entites', 'entite'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required',
            'entite_id' => 'required|exists:entites,id',
            'is_checked' => 'string',
        ]);
        $cde_note = new CdeNote();
        $cde_note->contenu = $request->contenu;
        $cde_note->ordre = CdeNote::where('entite_id', $request->entite_id)->count();
        $cde_note->is_checked = $request->is_checked == 'on' ? true : false;
        $cde_note->entite_id = $request->entite_id;
        $cde_note->save();
        return redirect()->route('administration.cdeNote.index', $request->entite_id)->with('success', 'Note Crée avec succès.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'contenu' => 'required',
            'entite_id' => 'required|exists:entites,id',
            'is_checked' => 'string',
        ]);
        $cde_note = CdeNote::findOrFail($id);
        $cde_note->contenu = $request->contenu;
        $cde_note->ordre = CdeNote::where('entite_id', $request->entite_id)->count();
        $cde_note->is_checked = $request->is_checked == 'on' ? true : false;
        $cde_note->entite_id = $request->entite_id;
        $cde_note->save();
        return redirect()->route('administration.cdeNote.index', $cde_note->entite_id)->with('success', 'Note modifiée avec succès.');
    }
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:cde_notes,id',
        ]);

        foreach ($request->order as $index => $id) {
            $cde_note = CdeNote::findOrFail($id);
            $cde_note->ordre = $index;
            $cde_note->save();
        }

        return response()->json(['success' => true, 'message' => 'Ordre mis à jour avec succès.']);
    }
    public function destroy($id)
    {
        $cde_note = CdeNote::findOrFail($id);
        $entite_id = $cde_note->entite_id;
        $cde_note->delete();
        return redirect()->route('administration.cdeNote.index', $entite_id)->with('success', 'Note supprimée avec succès.');
    }

}

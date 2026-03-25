<?php

namespace App\Http\Controllers;

use App\Models\Entite;
use Illuminate\Http\Request;
use Storage;

class AdministrationController extends Controller
{
    public function index()
    {
        return view('administration.index');
    }
    public function info($id = 1)
    {
        $entites = Entite::all();
        $entite = $id ? Entite::find($id) : null;
        return view('administration.info', compact('entites', 'entite'));
    }
    public function update(Request $request, $id)
    {
        $entite = Entite::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:255',
            'tel' => 'required|string|max:255',
            'siret' => 'required|string|max:255',
            'rcs' => 'required|string|max:255',
            'numero_tva' => 'required|string|max:255',
            'code_ape' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'horaires' => 'required|string|max:255',
        ]);

        if ($request->hasFile('logo') && !$request->file('logo')->isValid()) {
            return redirect()->back()->withErrors(['logo' => 'Le champ logo doit être une image valide.']);
        }
        $logoPath = $entite->logo;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo');
            $logoPath = $request->file('logo');
            $logoName = 'img/' . $request->name . '.' . $logoPath->getClientOriginalExtension();
            $logoPath->move(public_path('img'), $logoName);
            $logoPath = $logoName;
        }
        $entite->name = $request->name;
        $entite->adresse = $request->adresse;
        $entite->ville = $request->ville;
        $entite->code_postal = $request->code_postal;
        $entite->tel = $request->tel;
        $entite->siret = $request->siret;
        $entite->rcs = $request->rcs;
        $entite->numero_tva = $request->numero_tva;
        $entite->code_ape = $request->code_ape;
        $entite->logo = $logoPath;
        $entite->horaires = $request->horaires;
        $entite->save();

        return redirect()->route('administration.info_entite', $id);
    }
}

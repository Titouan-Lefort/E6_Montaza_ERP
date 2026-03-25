<?php

namespace App\Http\Controllers;

use App\Models\Mailtemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MailtemplateController extends Controller
{
    public function index()
    {
        $mailtemplates = Mailtemplate::all();
        $signaturePath = Storage::path('signature/signature.png');
        $signature = base64_encode(file_get_contents($signaturePath));
        return view('mailtemplates.index', compact('mailtemplates', 'signature'));
    }
    public function edit($id)
    {
        $mailtemplate = Mailtemplate::findOrFail($id);
        return view('mailtemplates.edit', compact('mailtemplate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sujet' => 'required|string',
            'contenu' => 'required|string',
        ]);
        $mailtemplate = Mailtemplate::findOrFail($id);
        $contenu = str_replace("CHEVRON-GAUCHE", "<", $request->contenu);
        $contenu = str_replace("CHEVRON-DROIT", ">", $contenu);
        $mailtemplate->sujet = $request->sujet;
        $mailtemplate->contenu = $contenu;
        $mailtemplate->save();
        return redirect()->route('mailtemplates.edit', $id)->with('success', 'Modèle de mail mis à jour avec succès');
    }
    public function uploadSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $file = $request->file('signature');
        $filename = 'signature.' . $file->extension();
        $file->storeAs('signature', $filename);
        return redirect()->route('mailtemplates.index')->with('success', 'Signature uploadée avec succès ');
    }
}

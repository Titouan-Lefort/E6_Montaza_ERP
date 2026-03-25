<?php

namespace App\Http\Controllers;

use App\Models\DevisTuyauterie;
use App\Models\Entite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\DevisEmail;

class DevisTuyauterieController extends Controller
{
    public function index()
    {
        // Récupérer les devis actifs triés par date d'émission décroissante
        $devis = DevisTuyauterie::where('is_archived', false)->orderBy('date_emission', 'desc')->get();
        return view('devis_tuyauterie.index', compact('devis'));
    }

    public function archives()
    {
        // Récupérer les devis archivés
        $devis = DevisTuyauterie::where('is_archived', true)->orderBy('date_emission', 'desc')->get();
        return view('devis_tuyauterie.archives', compact('devis'));
    }

    public function archive($id)
    {
        $devis = DevisTuyauterie::findOrFail($id);
        $devis->update(['is_archived' => true]);
        return redirect()->back()->with('success', 'Devis archivé avec succès.');
    }

    public function unarchive($id)
    {
        $devis = DevisTuyauterie::findOrFail($id);
        $devis->update(['is_archived' => false]);
        return redirect()->back()->with('success', 'Devis restauré avec succès.');
    }

    public function indexColDevisTuyauterieSmall()
    {
        $devis = DevisTuyauterie::where('is_archived', false)->orderBy('date_emission', 'desc')->take(7)->get();
        $isSmall = true;
        return view('devis_tuyauterie.index_col', compact('devis', 'isSmall'));
    }

    public function create(Request $request)
    {
        $affaire_id = $request->query('affaire_id');
        return view('devis_tuyauterie.create', compact('affaire_id'));
    }

    public function edit($id)
    {
        $devis = DevisTuyauterie::with(['sections.lignes'])->findOrFail($id);
        return view('devis_tuyauterie.edit', compact('devis'));
    }

    public function show($id)
    {
        $devis = DevisTuyauterie::with(['sections.lignes'])->findOrFail($id);
        return view('devis_tuyauterie.show', compact('devis'));
    }

    public function pdf($id)
    {
        $devis = DevisTuyauterie::with(['sections.lignes'])->findOrFail($id);
        $entite = Entite::first();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('devis_tuyauterie.pdf', compact('devis', 'entite'));
        $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

        $fileName = 'Devis_' . ($devis->reference_projet ?? $devis->id) . '.pdf';

        // Save to storage
        $year = $devis->created_at->format('Y');
        Storage::put('DevisTuyauterie/' . $year . '/' . $fileName, $pdf->output());

        return $pdf->stream($fileName);
    }

    public function preview($id)
    {
        $devis = DevisTuyauterie::findOrFail($id);
        return view('devis_tuyauterie.preview', compact('devis'));
    }

    public function downloadPdf($id)
    {
        $devis = DevisTuyauterie::findOrFail($id);
        $fileName = 'Devis_' . ($devis->reference_projet ?? $devis->id) . '.pdf';
        $year = $devis->created_at->format('Y');
        $path = 'DevisTuyauterie/' . $year . '/' . $fileName;

        if (!Storage::exists($path)) {
            // Generate if not exists
            return $this->pdf($id);
        }

        return Storage::download($path, $fileName);
    }

    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'email_destinataire' => 'required|email',
            'email_sujet' => 'required|string|max:255',
            'email_message' => 'nullable|string',
        ]);

        try {
            $devis = DevisTuyauterie::with(['sections.lignes', 'societeContact'])->findOrFail($id);
            $entite = Entite::first();

            // Générer le PDF s'il n'existe pas
            $fileName = 'Devis_' . ($devis->reference_projet ?? $devis->id) . '.pdf';
            $year = $devis->created_at->format('Y');
            $path = 'DevisTuyauterie/' . $year . '/' . $fileName;

            if (!Storage::exists($path)) {
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('devis_tuyauterie.pdf', compact('devis', 'entite'));
                $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
                Storage::put($path, $pdf->output());
            }

            // Récupérer le chemin complet du fichier
            $fullPath = storage_path('app/' . $path);

            // Récupérer l'utilisateur connecté
            $currentUser = \Auth::user();
            $senderEmail = $currentUser->email;
            $senderName = $currentUser->getName();

            // Envoyer l'email
            Mail::to($request->email_destinataire)->send(
                new DevisEmail(
                    $devis,
                    $request->email_sujet,
                    $request->email_message,
                    $fullPath,
                    $senderEmail,
                    $senderName
                )
            );

            return redirect()->route('devis_tuyauterie.preview', $id)
                ->with('email_success', 'Email envoyé avec succès à ' . $request->email_destinataire);
        } catch (\Exception $e) {
            return redirect()->route('devis_tuyauterie.preview', $id)
                ->with('email_error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage())
                ->withInput();
        }
    }
}

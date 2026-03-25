<?php

namespace App\Http\Controllers;

use App\Models\DossierStandard;
use App\Models\Standard;
use App\Models\StandardVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class StandardController extends Controller
{
    public function index() {
        $folders = DossierStandard::with('standards')->get()->sortBy('nom');
        $versions_count = StandardVersion::count();
        return cache()->remember('standards_dossiers', 60, function() use ($folders, $versions_count) {
            return view('standards.index', compact('folders', 'versions_count'))->render();
        });
    }
    public function create() {
        $folders = DossierStandard::with('standards')->get()->sortBy('nom');
        $versions_count = StandardVersion::count();
        $create = true;
            return view('standards.index', compact('folders', ['versions_count','create']))->render();
    }
    public function show($dossier, $standard) {
        $stockagePath = Storage::path('standards/' . $dossier);

        if (File::exists($stockagePath)) {
            $pdfFiles = File::files($stockagePath);

            foreach ($pdfFiles as $file) {
                if ($file->getFilename() === $standard) {
                    return response()->file($file->getPathname());
                }
            }
        }

        return back()->with('error', 'Fichier non trouvé.');
    }
    public function destroy(Request $request) {
        $standardVersion = StandardVersion::findOrFail($request->id);
        $standard = $standardVersion->standard;
        $noms_matieres = null;
        if ($standardVersion->matieres->count() > 0) {

            foreach ($standardVersion->matieres as $matiere) {
                $matiere->standard_version_id = null;
                $matiere->save();
                $noms_matieres[] = $matiere->designation;
            }
        }
        if ($standard->versions->count() === 1) {
            $standard->delete();
        } else {
            $standardVersion->delete();
        }
            Storage::delete($standardVersion->chemin_pdf);
        if (Storage::exists($standardVersion->chemin_pdf)) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression du fichier.');
        }
        cache()->forget('standards_dossiers');
        if ($noms_matieres !== null) {
            $noms_matieres = implode(',<br/> ', $noms_matieres);
            return back()->with('success', 'Standard ajouté avec succès. Les matières suivantes ont été mises à jour: ' . $noms_matieres);
        }
        return back()->with('success', 'Standard supprimé avec succès.');
    }
    public function showVersionsJson($dossier, $standard) {
    $standard = str_replace('.pdf', '', $standard);
    $standard = Standard::with('versions')->where('nom', $standard)->first();
    if (!$standard) {
        return response()->json([]);
    }
    $versions = $standard->versions->pluck('version')->toArray();
    return response()->json($versions);
    }
    public function store(Request $request) {
        $request->validate([
            'dossier' => 'required|exists:dossier_standards,id',
            'file' => 'required|file',
            'version' => 'required|alpha',
        ],
        [
            'dossier.required' => 'Le dossier est requis.',
            'dossier.exists' => 'Le dossier n\'existe pas.',
            'file.file' => 'Le standard doit être un fichier PDF.',
            'file.required' => 'Le standard est requis.',
            'version.required' => 'La version est requise.',
            'version.alpha' => 'La version doit être une lettre.',
        ]);

        $dossier = DossierStandard::findOrFail($request->dossier);
        $standardName = str_replace('.pdf','',$request->file('file')->getClientOriginalName());
        $standard = Standard::where('nom', $standardName)
                    ->where('dossier_standard_id', $dossier->id)
                    ->first();

        if (!$standard) {
            $standard = new Standard();
            $standard->nom = $standardName;
            $standard->dossier_standard_id = $dossier->id;
            $standard->save();
        }

        // Check if the version already exists for the standard
        $existingVersion = StandardVersion::where('standard_id', $standard->id)
                          ->where('version', $request->version)
                          ->first();
        if ($existingVersion) {
            return back()->with('error', 'Une version avec le même nom existe déjà pour ce standard.');
        }

        $version = new StandardVersion();
        $version->version = $request->version;
        $version->standard_id = $standard->id;
        $path = 'standards/' . $dossier->nom . '/' . $standardName . '.pdf';
        if (Storage::exists($path)) {
            $path = 'standards/' . $dossier->nom . '/' . $standardName . '_' . $version->version. '.pdf';
        }
        $version->chemin_pdf = $path;
        $path = $request->file('file')->storeAs('standards/' . $dossier->nom, basename($path));
        $version->save();

        cache()->forget('standards_dossiers');
        return back()->with('success', 'Standard ajouté avec succès.');
    }
    public function destroyDossier(Request $request) {
        $dossier = DossierStandard::findOrFail($request->id);
        $standards = $dossier->standards;
        $noms_matieres = null;
        foreach ($standards as $standard) {
            foreach ($standard->versions as $version) {
                if ($version->matieres->count() > 0) {

                    foreach ($version->matieres as $matiere) {
                        $matiere->standard_version_id = null;
                        $matiere->save();
                        $noms_matieres[] = $matiere->designation;
                    }
                }
                Storage::delete($version->chemin_pdf);
                $version->delete();
            }
            $standard->delete();
        }
        Storage::deleteDirectory('standards/' . $dossier->nom);
        $dossier->delete();
        cache()->forget('standards_dossiers');
        if ($noms_matieres !== null) {
            $noms_matieres = implode(',<br/> ', $noms_matieres);
            return back()->with('success', 'Dossier supprimé avec succès. Les matières suivantes ont été mises à jour: ' . $noms_matieres);
        }
        return back()->with('success', 'Dossier supprimé avec succès.');
    }
    public function storeDossier(Request $request) {
        $request->validate([
            'nom' => 'required|string|unique:dossier_standards,nom'
        ]);
        Storage::makeDirectory('standards/' . $request->nom);
        $dossier = new DossierStandard();
        $dossier->nom = $request->nom;
        $dossier->save();
        cache()->forget('standards_dossiers');
        return back()->with('success', 'Dossier '.$request->nom.' ajouté avec succès.');
            }
    public function showStandardsJson($dossier) {
        $dossier_id = DossierStandard::where('nom', $dossier)->first()->id;
        $standards = Standard::where('dossier_standard_id', $dossier_id)->get();
        return response()->json($standards);
    }
}

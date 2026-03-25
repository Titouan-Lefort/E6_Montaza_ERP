<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    public function index()
    {
        $documentationPath = storage_path('app/public/documentation/DocumentationUtilisateurapplicationlegere.html');

        if (!file_exists($documentationPath)) {
            return view('documentation.index', [
                'documentationContent' => '<div class="alert alert-danger"><h4>Documentation non trouvée</h4><p>Le fichier de documentation n\'existe pas à l\'emplacement attendu.</p></div>',
                'hasError' => true
            ]);
        }

        $content = file_get_contents($documentationPath);

        // Traiter le contenu pour les images et liens
        $content = $this->processDocumentationContent($content);

        return view('documentation.index', [
            'documentationContent' => $content,
            'hasError' => false
        ]);
    }

    public function download($format)
    {
        $allowedFormats = ['pdf', 'docx'];

        if (!in_array($format, $allowedFormats)) {
            abort(404, 'Format non supporté');
        }

        $filename = 'DocumentationUtilisateurapplicationlegere.' . $format;
        $filePath = storage_path('app/public/documentation/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'Fichier de documentation non trouvé: ' . $filename);
        }

        $mimeTypes = [
            'pdf' => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        $downloadName = 'Documentation_Utilisateur_Application.' . $format;

        return response()->download($filePath, $downloadName, [
            'Content-Type' => $mimeTypes[$format],
            'Content-Disposition' => 'attachment; filename="' . $downloadName . '"'
        ]);
    }

    private function processDocumentationContent($content)
    {
        $baseUrl = url('documentation/images/');

        // Remplacer les chemins d'images en capturant le nom du fichier
        $content = preg_replace(
            '/src="(?:\.\/)?(?:\.\.\/)?[Ii]mages\/([^"]+)"/',
            'src="' . $baseUrl . '/$1"',
            $content
        );

        // Ajuster aussi les liens href vers les images
        $content = preg_replace(
            '/href="(?:\.\/)?(?:\.\.\/)?[Ii]mages\/([^"]+)"/',
            'href="' . $baseUrl . '/$1"',
            $content
        );

        // Corriger les liens internes pour qu'ils restent dans la page
        $content = preg_replace('/href="(?!http|#|mailto:|' . preg_quote($baseUrl, '/') . ')([^"]*)"/', 'href="#"', $content);

        // Nettoyer le contenu pour extraire seulement le body
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
            $content = $matches[1];
        }

        return $content;
    }

    public function serveImage($filename)
    {
        $imagePath = storage_path('app/public/documentation/images/' . $filename);

        if (!file_exists($imagePath)) {
            abort(404, 'Image non trouvée: ' . $filename);
        }

        $mimeType = mime_content_type($imagePath);
        return response()->file($imagePath, ['Content-Type' => $mimeType]);
    }
}

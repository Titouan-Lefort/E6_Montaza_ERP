<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PreventDebugMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Journaliser les informations de la requête pour déboguer
        Log::info('Upload via QR code - Requête reçue', [
            'content_type' => $request->header('Content-Type'),
            'content_length' => $request->header('Content-Length'),
            'has_file' => $request->hasFile('files'),
            'all_inputs' => $request->all(),
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                Log::info("Fichier {$index} dans la requête", [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'error' => $file->getError(),
                ]);
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSSProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->input();
        if (empty($input)) {
            return $next($request);
        }
        // Parcours des champs pour nettoyer les valeurs
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                $value = str_replace(['<', '>', ';', '--'], 'CARACTERE_NON_AUTORISE', $value);
                $value = str_replace('\'','\'', $value);
            }
        });

        // Mise à jour de la requête avec les données nettoyées
        $request->merge($input);

        return $next($request);
    }
}

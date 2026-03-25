<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// ... existing code ...

// Route temporaire pour forcer la migration depuis le navigateur
Route::get('/dev/run-migrations', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return '<h1>Migrations exécutées</h1><pre>' . Artisan::output() . '</pre><a href="/">Retour à l\'accueil</a>';
    } catch (\Exception $e) {
        return '<h1>Erreur</h1><pre>' . $e->getMessage() . '</pre>';
    }
});

Route::get('/dev/run-tests', function () {
    try {
        // Optionnel : Effacer le cache config pour être sûr que phpunit.xml est pris en compte ou que l'environnement est propre
        Artisan::call('config:clear');

        // Exécuter les tests via Artisan
        // Note: Artisan::call('test') affichera la sortie formatée CLI.
        // On essaye de capturer le buffer.

        $exitCode = Artisan::call('test', ['--filter' => 'Devis']);
        $output = Artisan::output();

        // Convertir les codes couleurs ANSI en HTML basique pour lisibilitÃ©
        $output = preg_replace('/\e\[32m/', '<span style="color:green">', $output);
        $output = preg_replace('/\e\[31m/', '<span style="color:red">', $output);
        $output = preg_replace('/\e\[33m/', '<span style="color:orange">', $output);
        $output = preg_replace('/\e\[39m/', '</span>', $output);
        $output = preg_replace('/\e\[0m/', '</span>', $output);

        return '<h1>Exécution des Tests Unitaires (Filtre: Devis)</h1>
                <pre style="background: #1e1e1e; color: #cfcfcf; padding: 15px; border-radius: 5px; font-family: monospace;">' . $output . '</pre>
                <p>Code de sortie: ' . $exitCode . '</p>
                <a href="/">Retour à l\'accueil</a>';
    } catch (\Exception $e) {
        return '<h1>Erreur lors des tests</h1><pre>' . $e->getMessage() . '</pre><pre>' . $e->getTraceAsString() . '</pre>';
    }
});

// Route de diagnostic pour vérifier les médias
Route::get('/dev/check-media', function () {
    $medias = \App\Models\Media::latest()->take(10)->get();
    $html = '<h1>Diagnostic des Médias</h1>';
    $html .= '<p>Total médias: ' . \App\Models\Media::count() . '</p>';
    $html .= '<h2>Derniers 10 médias</h2>';
    $html .= '<table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">';
    $html .= '<tr><th>ID</th><th>Fichier</th><th>Chemin</th><th>Type</th><th>Taille</th><th>Existe?</th></tr>';

    foreach ($medias as $media) {
        $fullPath = \Storage::disk('public')->path($media->path);
        $exists = file_exists($fullPath) ? '✅ OUI' : '❌ NON';
        $html .= '<tr>';
        $html .= '<td>' . $media->id . '</td>';
        $html .= '<td>' . $media->original_filename . '</td>';
        $html .= '<td><small>' . $media->path . '</small><br><small style="color: blue;">' . $fullPath . '</small></td>';
        $html .= '<td>' . $media->mime_type . '</td>';
        $html .= '<td>' . number_format($media->size / 1024, 2) . ' Ko</td>';
        $html .= '<td>' . $exists . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $html .= '<h2>Configuration Storage</h2>';
    $html .= '<pre>Public path: ' . storage_path('app/public') . '</pre>';
    $html .= '<pre>Public disk root: ' . \Storage::disk('public')->path('') . '</pre>';

    $html .= '<h2>Actions</h2>';
    $html .= '<p><a href="/dev/clean-orphan-media" style="padding: 10px 20px; background: orange; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">🧹 Nettoyer les médias orphelins</a></p>';
    $html .= '<p><a href="/dev/fix-storage-permissions" style="padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">🔧 Vérifier les permissions</a></p>';

    $html .= '<br><a href="/">Retour à l\'accueil</a>';

    return $html;
});

// Route pour nettoyer les médias orphelins (en base mais pas sur disque)
Route::get('/dev/clean-orphan-media', function () {
    $orphans = [];
    $medias = \App\Models\Media::all();

    foreach ($medias as $media) {
        $fullPath = \Storage::disk('public')->path($media->path);
        if (!file_exists($fullPath)) {
            $orphans[] = $media;
        }
    }

    $html = '<h1>Nettoyage des Médias Orphelins</h1>';
    $html .= '<p>Médias en base sans fichier physique : ' . count($orphans) . '</p>';

    if (count($orphans) > 0) {
        $html .= '<form method="POST" action="/dev/delete-orphan-media">';
        $html .= csrf_field();
        $html .= '<table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">';
        $html .= '<tr><th>Sélection</th><th>ID</th><th>Fichier</th><th>Chemin manquant</th><th>Date création</th></tr>';

        foreach ($orphans as $media) {
            $html .= '<tr>';
            $html .= '<td><input type="checkbox" name="media_ids[]" value="' . $media->id . '" checked></td>';
            $html .= '<td>' . $media->id . '</td>';
            $html .= '<td>' . $media->original_filename . '</td>';
            $html .= '<td><small>' . $media->path . '</small></td>';
            $html .= '<td>' . $media->created_at->format('d/m/Y H:i') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '<br><button type="submit" style="padding: 10px 20px; background: red; color: white; border: none; border-radius: 5px; cursor: pointer;">Supprimer les médias sélectionnés</button>';
        $html .= '</form>';
    } else {
        $html .= '<p style="color: green;">✅ Aucun média orphelin trouvé</p>';
    }

    $html .= '<br><a href="/dev/check-media">← Retour au diagnostic</a> | <a href="/">Accueil</a>';

    return $html;
});

Route::post('/dev/delete-orphan-media', function (\Illuminate\Http\Request $request) {
    $mediaIds = $request->input('media_ids', []);
    $count = 0;

    foreach ($mediaIds as $id) {
        $media = \App\Models\Media::find($id);
        if ($media) {
            $media->delete();
            $count++;
        }
    }

    return redirect('/dev/check-media')->with('message', $count . ' médias orphelins supprimés');
});

// Route pour vérifier et corriger les permissions storage
Route::get('/dev/fix-storage-permissions', function () {
    $storagePath = storage_path('app/public');
    $publicPath = public_path('storage');

    $html = '<h1>Diagnostic et Correction des Permissions Storage</h1>';

    // Vérifier si le répertoire storage/app/public existe
    $html .= '<h2>1. Répertoire storage/app/public</h2>';
    if (is_dir($storagePath)) {
        $html .= '<p>✅ Existe : ' . $storagePath . '</p>';
        $perms = substr(sprintf('%o', fileperms($storagePath)), -4);
        $html .= '<p>Permissions actuelles : ' . $perms . '</p>';

        if (is_writable($storagePath)) {
            $html .= '<p>✅ Accessible en écriture</p>';
        } else {
            $html .= '<p style="color: red;">❌ NON accessible en écriture</p>';
            if (PHP_OS_FAMILY !== 'Windows') {
                $html .= '<p>Exécutez : <code>chmod -R 775 ' . $storagePath . '</code></p>';
            }
        }
    } else {
        $html .= '<p style="color: red;">❌ Le répertoire n\'existe pas</p>';
    }

    // Vérifier le lien symbolique
    $html .= '<h2>2. Lien Symbolique public/storage</h2>';
    if (is_link($publicPath)) {
        $target = readlink($publicPath);
        $html .= '<p>✅ Lien symbolique existe</p>';
        $html .= '<p>Cible : ' . $target . '</p>';

        if (realpath($target) === realpath($storagePath)) {
            $html .= '<p>✅ Pointe vers le bon répertoire</p>';
        } else {
            $html .= '<p style="color: orange;">⚠️ Pointe vers : ' . realpath($target) . '</p>';
            $html .= '<p style="color: orange;">⚠️ Devrait pointer vers : ' . realpath($storagePath) . '</p>';
        }
    } elseif (is_dir($publicPath)) {
        $html .= '<p style="color: orange;">⚠️ Existe en tant que répertoire (au lieu d\'un lien symbolique)</p>';
    } else {
        $html .= '<p style="color: red;">❌ N\'existe pas</p>';
        $html .= '<p>Exécutez : <code>php artisan storage:link</code></p>';
    }

    // Vérifier le répertoire media
    $mediaPath = $storagePath . '/media';
    $html .= '<h2>3. Répertoire media</h2>';
    if (is_dir($mediaPath)) {
        $html .= '<p>✅ Existe : ' . $mediaPath . '</p>';
        if (is_writable($mediaPath)) {
            $html .= '<p>✅ Accessible en écriture</p>';
        } else {
            $html .= '<p style="color: red;">❌ NON accessible en écriture</p>';
        }
    } else {
        $html .= '<p style="color: orange;">⚠️ N\'existe pas encore (sera créé au premier upload)</p>';
    }

    // Configuration Laravel
    $html .= '<h2>4. Configuration Laravel</h2>';
    $html .= '<pre>FILESYSTEM_DISK=' . config('filesystems.default') . '</pre>';
    $html .= '<pre>Public disk root: ' . config('filesystems.disks.public.root') . '</pre>';
    $html .= '<pre>Public disk url: ' . config('filesystems.disks.public.url') . '</pre>';

    $html .= '<br><a href="/dev/check-media">← Retour au diagnostic</a> | <a href="/">Accueil</a>';

    return $html;
});

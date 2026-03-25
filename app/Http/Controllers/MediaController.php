<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Cde;
use App\Models\Commentaire;
use App\Models\Ddp;
use App\Models\MediaType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class MediaController extends Controller
{
    /**
     * Les classes de modèles autorisées pour l'attachement des médias
     */
    protected $allowedModels = [
        'cde' => Cde::class,
        'ddp' => Ddp::class,
        // Ajoutez d'autres modèles selon vos besoins
    ];

    public const AUTHORIZED_MIME_TYPES = Media::AUTHORIZED_MIME_TYPES;

    /**
     * Affiche la page d'accueil du gestionnaire de médias.
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'type' => 'nullable|exists:media_types,id',
            'nombre' => 'nullable|integer',
        ]);
        $medias = Media::query()
            ->when($request->input('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('original_filename', 'ilike', '%' . $search . '%')
                        ->orWhere('filename', 'ilike', '%' . $search . '%')
                        ->orWhereHas('uploader', function ($u) use ($search) {
                            $u->where('first_name', 'ilike', '%' . $search . '%')
                                ->orWhere('last_name', 'ilike', '%' . $search . '%');
                        });
                });
            })
            ->when($request->input('type'), function ($query, $mediaTypeId) {
                return $query->where('media_type_id', $mediaTypeId);
            })
            ->with(['user', 'mediaType', 'mediaable'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('nombre', 50));

        $media_types = MediaType::all();

        // Grouper par modèle lié ET par id de l'entité liée
        $groupedMedias = $medias->getCollection()
            ->groupBy(function($media) {
                return $media->mediaable_type;
            })
            ->map(function($group) {
                return $group->groupBy(function($media) {
                    return $media->mediaable_id;
                });
            });

        return view('media.index', [
            'medias' => $medias,
            'media_types' => $media_types,
            'groupedMedias' => $groupedMedias,
        ]);
    }



    /**
     * Affiche la liste des médias associés à une entité.
     */
    public function indexModel($model, $id)
    {
        // Vérifier si l'entité existe
        $entity = $this->getEntity($model, $id);

        if (!$entity) {
            return abort(404);
        }

        return view('media.indexModel', [
            'model' => $model,
            'modelId' => $id,
            'entity' => $entity
        ]);
    }

    /**
     * Enregistre un ou plusieurs fichiers associés à une entité.
     */
    public function store(Request $request, $model, $id)
    {
        Log::info(json_encode([$request->all(),implode(',', self::AUTHORIZED_MIME_TYPES)]));
        $request->validate([
            'files.*' => 'required|file|max:20480|mimetypes:' . implode(',', self::AUTHORIZED_MIME_TYPES),
            'media_type_id' => 'nullable|exists:media_types,id',
        ]);

        $entity = $this->getEntity($model, $id);

        if (!$entity) {
            return back()->withErrors(['Entité non trouvée']);
        }

        $successCount = 0;
        $errorFiles = [];

        foreach ($request->file('files') as $file) {
            try {
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = 'media/' . $model . '/' . date('Y/m/d');

                // Utilisation de la méthode storeAs avec gestion de disque spécifique
                $filePath = $file->storeAs($path, $filename, 'public');

                $media = new Media([
                    'filename' => $filename,
                    'original_filename' => $originalFilename,
                    'path' => $filePath,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::getUser()->id,
                    'media_type_id' => $request->media_type_id, // Ajouter le type de média
                ]);

                $entity->media()->save($media);
                $successCount++;
            } catch (\Exception $e) {
                Log::error('Erreur lors du téléchargement du fichier', [
                    'file' => $originalFilename ?? 'inconnu',
                    'error' => $e->getMessage()
                ]);
                $errorFiles[] = $originalFilename ?? 'Un fichier';
            }
        }

        if (count($errorFiles) > 0) {
            return back()->with('warning', 'Certains fichiers n\'ont pas pu être téléchargés: ' . implode(', ', $errorFiles))
                ->with('success', $successCount . ' fichier(s) téléchargé(s) avec succès');
        }

        return back()->with('success', 'Fichiers téléchargés avec succès');
    }

    /**
     * Affiche un média.
     */
    public function show($mediaid)
    {
        // Vérification des autorisations si nécessaire
        // Cette partie peut être adaptée selon vos règles d'autorisation

        $media = Media::find($mediaid);

        if (!$media) {
            abort(404, 'Média non trouvé');
        }

        $path = $media->path;
        if (is_null($path)) {
            abort(404, 'Chemin du fichier non défini');
        }

        $fullPath = Storage::disk('public')->path($path);

        if (!file_exists($fullPath)) {
            \Log::error('Fichier média non trouvé', [
                'media_id' => $mediaid,
                'path' => $path,
                'full_path' => $fullPath
            ]);
            abort(404, 'Fichier non trouvé');
        }

        $type = $media->mime_type ?? 'application/octet-stream';
        $fileContent = file_get_contents($fullPath);

        $response = response($fileContent, 200);
        $response->header('Content-Type', $type);
        // Encode the filename for Content-Disposition header
        $encodedFilename = rawurlencode($media->original_filename);
        $response->header('Content-Disposition', "inline; filename=\"{$media->original_filename}\"; filename*=UTF-8''{$encodedFilename}");

        return $response;
    }

    /**
     * Télécharge un média.
     */
    public function download($mediaId)
    {
        // Vérification des autorisations si nécessaire
        // Cette partie peut être adaptée selon vos règles d'autorisation

        $media = Media::find($mediaId);
        if (!$media) {
            abort(404, 'Média non trouvé');
        }

        if (is_null($media->path)) {
            abort(404, 'Chemin du fichier non défini');
        }
        $path = Storage::disk('public')->path($media->path);
        return response()->download($path, $media->original_filename);
    }

    /**
     * Supprime un média.
     */
    public function destroy(Media $media)
    {
        Storage::delete($media->path);
        $media->delete();
        return back()->with('success', 'Fichier supprimé avec succès');
    }

    /**
     * Génère un lien signé pour l'upload via QR code.
     */
    public function generateQrLink($model, $id)
    {
        try {
            $token = Str::random(32);

            // Augmenter la durée de validité à 1 heures pour éviter les problèmes d'expiration
            $signedUrl = URL::temporarySignedRoute(
                'media.upload-form',
                now()->addHours(1),
                [
                    'model' => $model,
                    'id' => $id,
                    'token' => $token
                ]
            );

            $qrCodeSvg = QrCode::size(200)->generate($signedUrl);

            return response()->json([
                'success' => true,
                'qrCodeHtml' => $qrCodeSvg->toHtml()
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du QR code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Traite l'upload de fichiers via QR code.
     */
    public function uploadFromQr(Request $request, $model, $id, $token)
    {
        try {
            // Vérifier si l'URL est signée
            if (!$request->hasValidSignature()) {
                Log::error('Signature invalide pour upload QR code', [
                    'model' => $model,
                    'id' => $id,
                    'token' => $token
                ]);
                return back()->withErrors(['Le lien a expiré ou est invalide. Veuillez scanner à nouveau le QR code.']);
            }

            $request->validate([
                'files.*' => 'required|file|max:5120|mimes:' . str_replace('.', '', implode(',', Media::AUTHORIZED_FILE_EXTENSIONS)),
                'media_type_id' => 'nullable|exists:media_types,id',
            ]);

            $entity = null;
            if (isset($this->allowedModels[$model])) {
                $entityClass = $this->allowedModels[$model];
                $entity = $entityClass::findOrFail($id);
            }

            if (!$entity) {
                return back()->withErrors(['Entité non trouvée']);
            }

            // Vérifier que le répertoire de destination existe
            $basePath = 'media/' . $model . '/' . date('Y/m/d');
            $fullPath = $basePath;

            if (!Storage::disk('public')->exists($fullPath)) {
                Storage::disk('public')->makeDirectory($fullPath);
            }

            foreach ($request->file('files') as $file) {
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;

                // Enregistrer le fichier
                $filePath = $file->storeAs($fullPath, $filename, 'public');

                // Créer l'entrée media
                $media = new Media([
                    'filename' => $filename,
                    'original_filename' => $originalFilename,
                    'path' => $filePath,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => 1, // System upload
                    'media_type_id' => $request->media_type_id, // Ajouter le type de média
                ]);

                // Associer à l'entité
                $entity->media()->save($media);
            }

            return back()->with('success', 'Fichiers téléchargés avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload via QR code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['Une erreur est survenue lors de l\'upload: ' . $e->getMessage()]);
        }
    }

    /**
     * Affiche le formulaire d'upload via QR code.
     */
    public function showUploadForm($model, $id, $token)
    {
        $entity = null;
        if (isset($this->allowedModels[$model])) {
            $entityClass = $this->allowedModels[$model];
            $entity = $entityClass::findOrFail($id);
        }

        return view('media.upload-form', [
            'model' => $model,
            'id' => $id,
            'token' => $token,
            'entity' => $entity
        ]);
    }

    /**
     * Récupère la classe du modèle à partir du nom fourni.
     */
    protected function getModelClass($model)
    {
        if (!isset($this->allowedModels[$model])) {
            abort(404, 'Type de modèle non autorisé');
        }

        return $this->allowedModels[$model];
    }

    protected function getEntity($model, $id)
    {
        if (!isset($this->allowedModels[$model])) {
            return null;
        }
        $entityClass = $this->allowedModels[$model];
        return $entityClass::find($id);
    }

    /**
     * Met à jour un média.
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'original_filename' => 'required|string|max:255',
            'media_type_id' => 'nullable|exists:media_types,id',
        ]);

        $media->update([
            'original_filename' => $request->original_filename,
            'media_type_id' => $request->media_type_id,
        ]);

        return back()->with('success', 'Pièce jointe mise à jour avec succès');
    }

    public function updateCommentaire(Request $request, $id)
    {
        $media = Media::find($id);
        if ($media) {
            // Trouve le commentaire lié au media
            $commentaire = $media->commentaire;
            if ($commentaire) {
                if ($commentaire->contenu == $request->commentaire) {
                    return response()->json(['message' => 'Commentaire inchangé'], 200);
                }
                // Met à jour le commentaire avec la nouvelle valeur
                $commentaire->contenu = $request->commentaire;
                $commentaire->save();
            } else {
                // Si le media n'a pas encore de commentaire, on en crée un
                $commentaire = new Commentaire();
                $commentaire->contenu = $request->commentaire;
                $commentaire->save();
                $media->commentaire()->associate($commentaire);
                $media->save();
            }
        }

        return response()->json(['message' => 'Commentaire mis à jour'], 200);
    }
    public function updateType(Request $request, $id)
    {
        $media = Media::find($id);
        if (!$media) {
            return response()->json(['message' => 'Média non trouvé'], 404);
        }

        $request->validate([
            'media_type_id' => 'required|exists:media_types,id',
        ]);

        $media->media_type_id = $request->media_type_id;
        $media->save();

        return response()->json(['message' => 'Type de média mis à jour avec succès'], 200);
    }
}

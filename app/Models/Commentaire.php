<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Commentaire extends Model
{
    /** @use HasFactory<\Database\Factories\CommentaireFactory> */
    use HasFactory;
    protected $fillable = [
        'contenu',
    ];
    public function societes()
    {
        return $this->hasMany(Societe::class);
    }
    public function medias(): HasMany {
        return $this->hasMany(Media::class);
    }

    protected static function booted(): void
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model): void {
            if (empty($model->commentaire)) {
            return;
            }
            self::logChange($model, 'creating');
        });

        // Enregistrer avant la mise à jour d'un modèle
        static::updating(function ($model): void {
            if ($model->isDirty('remember_token')) {
                return;
            }
            self::logChange($model, 'updating');
        });

        // Enregistrer avant la suppression d'un modèle
        static::deleting(function ($model): void {
            self::logChange($model, 'deleting');
        });
    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Commentaire',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
}

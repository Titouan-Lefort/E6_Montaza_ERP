<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MouvementStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'matiere_id',
        'user_id',
        'type',
        'quantite',
        'valeur_unitaire',
        'raison',
        'date',
        'cde_ligne_id',
    ];
    protected static function booted(): void
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model): void {
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
            'model_type' => 'MouvementStock',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cdeLigne()
    {
        return $this->belongsTo(CdeLigne::class, 'cde_ligne_id');
    }
    public function cde(): HasOneThrough
    {
        return $this->hasOneThrough(
            Cde::class,
            CdeLigne::class,
            'id', // Foreign key on CdeLigne table
            'id', // Foreign key on Cde table
            'cde_ligne_id', // Local key on MouvementStock table
            'cde_id' // Local key on CdeLigne table
        );
    }
}

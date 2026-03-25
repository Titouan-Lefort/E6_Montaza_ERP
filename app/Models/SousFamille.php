<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SousFamille extends Model
{
    protected $fillable = ['nom', 'famille_id', 'type_affichage_stock'];
    public $timestamps = false;

    protected static function booted(): void
    {
        static::created(function ($model): void {
            self::logChange($model, 'creating');
        });

        static::updating(function ($model): void {
            self::logChange($model, 'updating');
        });

        static::deleting(function ($model): void {
            self::logChange($model, 'deleting');
        });
        static::addGlobalScope('orderByName', function ($query) {
            $query->orderBy('nom');
        });
    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'SousFamille',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

    public function famille()
    {
        return $this->belongsTo(Famille::class);
    }
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }
}

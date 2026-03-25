<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionPaiement extends Model
{
    /** @use HasFactory<\Database\Factories\ConditionPaiementFactory> */
    use HasFactory;

    protected $fillable = ['nom'];

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
        static::addGlobalScope('orderByNom', function ($query) {
            $query->orderBy('nom');
        });
    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'ConditionPaiement',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

    public function cdes()
    {
        return $this->hasMany(Cde::class);
    }
    public function societes()
    {
        return $this->hasMany(Societe::class);
    }
}

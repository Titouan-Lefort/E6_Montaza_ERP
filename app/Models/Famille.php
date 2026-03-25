<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Famille extends Model
{
    protected $fillable = ['nom'];
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
    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Famille',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

    public function sousFamilles()
    {
        return $this->hasMany(SousFamille::class)->orderBy('nom');
    }
}

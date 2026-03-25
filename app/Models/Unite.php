<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Unite extends Model
{
    protected $fillable = ['short', 'full', 'full_plural', 'type'];
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
            'model_type' => 'Unite',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }
}

<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    /** @use HasFactory<\Database\Factories\PaysFactory> */
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
    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Pays',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    public function etablissements()
    {
        return $this->hasMany(Etablissement::class, 'pay_id');
    }
}

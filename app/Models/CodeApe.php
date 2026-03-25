<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeApe extends Model
{
    /** @use HasFactory<\Database\Factories\CodeApeFactory> */
    use HasFactory;

    protected $fillable = ['code', 'nom'];

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
        static::addGlobalScope('orderByCode', function ($query) {
            $query->orderBy('code');
        });
    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'CodeApe',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    public function societes()
    {
        return $this->hasMany(Societe::class, 'code_ape_id');
    }
}

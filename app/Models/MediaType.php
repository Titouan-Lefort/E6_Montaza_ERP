<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaType extends Model
{
    protected $table = 'media_types';

    protected $fillable = [
    'nom',
    'background_color_light',
    'background_color_dark',
    'text_color_light',
    'text_color_dark',
];

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
            'model_type' => 'MediaType',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'media_type_id');
    }
}

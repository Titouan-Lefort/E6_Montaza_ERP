<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CdeNote extends Model
{
    protected $fillable = [
        'contenu',
        'ordre',
        'is_checked',
        'entite_id',
    ];
    protected static function boot()
    {
        parent::boot();

        // Ajout d'une portée globale pour trier par 'ordre'
        static::addGlobalScope('ordre', function ($query) {
            $query->orderBy('ordre');
        });
    }

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
            'model_type' => 'CdeNote',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

    public function cdenote()
    {
        return $this->hasMany(CdeNote::class);
    }
    public function cdeCdeNote()
    {
        return $this->hasMany(CdeCdeNote::class);
    }
    public function cdes()
    {
        return $this->belongsToMany(Cde::class, 'cde_cde_notes', 'cde_note_id', 'cde_id');
    }
    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }
    public function isChecked()
    {
        return $this->is_checked;
    }
}

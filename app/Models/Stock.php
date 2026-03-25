<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'matiere_id',
        'quantite',
        'valeur_unitaire',
        'certificat',
    ];

    protected static function booted(): void
    {
        // "éviter la création de stock avec une quantité et une valeur unitaire à 0"
        static::created(function ($model): void {
            if ($model->valeur_unitaire == 0 && $model->quantite == 0) {
                $model->delete();
            }
        });

    }
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}

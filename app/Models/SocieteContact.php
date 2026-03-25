<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocieteContact extends Model
{
    /** @use HasFactory<\Database\Factories\SocieteContactFactory> */
    use HasFactory;

    protected $fillable = [
        'etablissement_id',
        'nom',
        'email',
        'telephone_portable',
        'telephone_fixe',
        'fonction',
    ];
    protected static function booted()
    {
        static::addGlobalScope('orderByNom', function ($query) {
            $query->orderBy('nom');
        });
    }
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }
    public function societe()
    {
        return $this->hasOneThrough(
            Societe::class,        // Modèle final
            Etablissement::class,  // Modèle intermédiaire
            'societe_id',          // Clé étrangère sur le modèle intermédiaire
            'id',                  // Clé étrangère sur le modèle final
            'etablissement_id',    // Clé locale sur le modèle actuel
            'id'                   // Clé locale sur le modèle intermédiaire
        );
    }
    public function ddpLigneFournisseurs(): HasMany
    {
        return $this->hasMany(DdpLigneFournisseur::class);
    }
}

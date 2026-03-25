<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DdpLigne extends Model
{
    /** @use HasFactory<\Database\Factories\DdpLigneFactory> */
    use HasFactory;

    protected $fillable = [
        'ddp_id',
        'matiere_id',
        'quantite',
        'ligne_autre_id',
        'case_ref',
        'case_designation',
        'case_quantite'
    ];

    public function ddp(): BelongsTo
    {
        return $this->belongsTo(Ddp::class);
    }
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }
    public function ddpLigneFournisseur(): HasMany
    {
        return $this->hasMany(DdpLigneFournisseur::class);
    }
    public function ddpLigneFournisseurs()
    {
        return $this->hasMany(DdpLigneFournisseur::class, 'ddp_ligne_id', 'id');
    }

    public function fournisseurs(): BelongsToMany
    {
        return $this->belongsToMany(Societe::class, 'ddp_ligne_fournisseurs', 'ddp_ligne_id', 'societe_id');
    }
    public function unite(): BelongsTo
    {
        return $this->belongsTo(Unite::class);
    }
}

<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Ddp extends Model
{
    /** @use HasFactory<\Database\Factories\DdpFactory> */
    use HasFactory;
    use MediaableTrait;

    protected $fillable = [
        'code',
        'nom',
        'entite_id',
        'ddp_cde_statut_id',
        'old_statut',
        'user_id',
        'dossier_suivi_par_id',
        'afficher_destinataire',
        'commentaire_id',
    ];

    public function statut(): BelongsTo
    {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }
    public function ddpCdeStatut(): BelongsTo
    {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function ddpLigne(): HasMany
    {
        return $this->hasMany(DdpLigne::class);
    }
    public function ddpLignes()
    {
        return $this->hasMany(DdpLigne::class, 'ddp_id', 'id');
    }
    public function ddpLigneFournisseur(): HasManyThrough
    {
        return $this->hasManyThrough(DdpLigneFournisseur::class, DdpLigne::class);
    }
    public function ddpLigneFournisseurs(): HasManyThrough
    {
        return $this->hasManyThrough(DdpLigneFournisseur::class, DdpLigne::class);
    }


    public function SocieteContacts()
    {
        return SocieteContact::whereHas('ddpLigneFournisseurs', function ($query) {
            $query->whereHas('ddpLigne', function ($query) {
                $query->where('ddp_id', $this->id);
            });
        })->get();
    }

    public function dossierSuiviPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dossier_suivi_par_id');
    }
    public function entite(): BelongsTo
    {
        return $this->belongsTo(Entite::class);
    }
    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class);
    }
    public function affaire(): BelongsTo
    {
        return $this->belongsTo(Affaire::class);
    }
}

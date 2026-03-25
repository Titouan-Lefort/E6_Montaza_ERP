<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class SocieteMatierePrix extends Model
{
    protected $table = 'societe_matiere_prixs';

    protected $fillable = [
        'societe_matiere_id',
        'prix_unitaire',
        'description',
        'date',
        'ddp_ligne_fournisseur_id',
        'cde_ligne_id'
    ];

    public function societeMatiere()
    {
        return $this->belongsTo(SocieteMatiere::class);
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class);
    }

    public function matiere()
    {
        return $this->hasOneThrough(
            Matiere::class,
            SocieteMatiere::class,
            'id',
            'id',
            'societe_matiere_id',
            'matiere_id'
        );
    }

    public function societe()
    {
        return $this->hasOneThrough(
            Societe::class,
            SocieteMatiere::class,
            'id',
            'id',
            'societe_matiere_id',
            'societe_id'
        );
    }

    public function cdeLigne()
    {
        return $this->belongsTo(CdeLigne::class, 'cde_ligne_id');
    }

    public function cde()
    {
        return $this->hasOneThrough(
            Cde::class,
            CdeLigne::class,
            'id',
            'id',
            'cde_ligne_id',
            'cde_id'
        );
    }

    public function ddpLigneFournisseur(): BelongsTo
    {
        return $this->belongsTo(DdpLigneFournisseur::class, 'ddp_ligne_fournisseur_id');
    }

    public function ddpLigne(): HasOneThrough
    {
        return $this->hasOneThrough(
            DdpLigne::class,
            DdpLigneFournisseur::class,
            'id',
            'id',
            'ddp_ligne_fournisseur_id',
            'ddp_ligne_id'
        );
    }

    // Correction: Ddp is reached through DdpLigneFournisseur -> DdpLigne -> Ddp
    public function ddp()
    {
        return $this->ddpLigneFournisseur()
            ->with('ddpLigne.ddp')
            ->get()
            ->first()
            ->ddpLigne
            ->ddp ?? null;
    }

    // Ou mieux encore, une relation directe :
    public function ddpViaLigne()
    {
        return $this->hasOneThrough(
            Ddp::class,
            DdpLigneFournisseur::class,
            'id',
            'id',
            'ddp_ligne_fournisseur_id',
            'ddp_ligne_id'
        )->join('ddp_lignes', 'ddp_lignes.id', '=', 'ddp_ligne_fournisseurs.ddp_ligne_id')
         ->whereColumn('ddps.id', 'ddp_lignes.ddp_id');
    }
}

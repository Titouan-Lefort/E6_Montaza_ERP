<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use App\Models\DdpLigne;

class DdpLigneFournisseur extends Model
{
    /** @use HasFactory<\Database\Factories\DdpLigneFournisseurFactory> */
    use HasFactory;

    protected $fillable = ['ddp_ligne_id', 'societe_id', 'ddp_cde_statut_id', 'societe_contact_id'];

    public function ddpLigne(): BelongsTo
    {
        return $this->belongsTo(DdpLigne::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Societe::class);
    }
    public function societe(): BelongsTo
    {
        return $this->belongsTo(Societe::class);
    }
    public function ddp(): HasOneThrough
    {
        return $this->hasOneThrough(Ddp::class, DdpLigne::class, 'id', 'id', 'ddp_ligne_id', 'ddp_id');
    }
    public function destinataire(): BelongsTo
    {
        return $this->belongsTo(SocieteContact::class);
    }
    public function societeContact()
    {
        return $this->belongsTo(SocieteContact::class, 'societe_contact_id', 'id');
    }

    public function statut(): BelongsTo
    {
        return $this->belongsTo(DdpCdeStatut::class);
    }
}

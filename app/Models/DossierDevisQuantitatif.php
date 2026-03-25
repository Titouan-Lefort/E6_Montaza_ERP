<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierDevisQuantitatif extends Model
{
    use HasFactory;

    protected $table = 'dossiers_devis_quantitatifs';

    protected $fillable = [
        'dossier_devis_id',
        'matiere_id',
        'categorie',
        'type',
        'designation',
        'description_technique',
        'reference',
        'quantite',
        'unite',
        'quantite_matiere_unitaire',
        'unite_matiere',
        'prix_achat',
        'prix_unitaire',
        'remarques',
        'ordre',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'quantite_matiere_unitaire' => 'decimal:6',
        'prix_achat' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
    ];

    /**
     * Relations
     */
    public function dossierDevis()
    {
        return $this->belongsTo(DossierDevis::class);
    }

    public function matiere()
    {
        return $this->belongsTo(\App\Models\Matiere::class);
    }
}

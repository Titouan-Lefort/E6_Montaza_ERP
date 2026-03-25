<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AffairePersonnel extends Pivot
{
    protected $table = 'affaire_personnel';

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Relation vers l'affaire
     */
    public function affaire()
    {
        return $this->belongsTo(Affaire::class);
    }

    /**
     * Relation vers le personnel
     */
    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    /**
     * Relation vers les tâches
     */
    public function taches()
    {
        return $this->hasMany(AffairePersonnelTache::class, 'affaire_personnel_id');
    }
}

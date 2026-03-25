<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffairePersonnelTache extends Model
{
    use HasFactory;

    protected $fillable = [
        'affaire_personnel_id',
        'titre',
        'description',
        'date_debut',
        'creneau_debut',
        'date_fin',
        'creneau_fin',
        'statut',
        'priorite',
        'ordre',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Statuts disponibles
     */
    const STATUT_A_FAIRE = 'a_faire';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_TERMINE = 'termine';

    /**
     * Priorités disponibles
     */
    const PRIORITE_BASSE = 'basse';
    const PRIORITE_NORMALE = 'normale';
    const PRIORITE_HAUTE = 'haute';

    public static function getStatuts()
    {
        return [
            self::STATUT_A_FAIRE => 'À faire',
            self::STATUT_EN_COURS => 'En cours',
            self::STATUT_TERMINE => 'Terminé',
        ];
    }

    public static function getPriorites()
    {
        return [
            self::PRIORITE_BASSE => 'Basse',
            self::PRIORITE_NORMALE => 'Normale',
            self::PRIORITE_HAUTE => 'Haute',
        ];
    }

    public function getStatutLabelAttribute()
    {
        return self::getStatuts()[$this->statut] ?? $this->statut;
    }

    public function getPrioriteLabelAttribute()
    {
        return self::getPriorites()[$this->priorite] ?? $this->priorite;
    }

    /**
     * Relation vers la ligne de la table pivot affaire_personnel
     */
    public function affairePersonnel()
    {
        return $this->belongsTo(AffairePersonnel::class, 'affaire_personnel_id');
    }
}

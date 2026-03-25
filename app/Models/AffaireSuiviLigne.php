<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffaireSuiviLigne extends Model
{
    use HasFactory;

    protected $table = 'affaire_suivi_lignes';

    protected $fillable = [
        'affaire_id',
        'projet',
        'tm',
        'indice',
        'activite',
        'stade_montage',
        'lot',
        'bloc',
        'panneau',
        'repere',
        'date_reception_iso',
        'fournisseur_prefa',
        'classe',
        'code_ts',
        'traitement_surface',
        'schema_ligne',
        'trigramme',
        'longueur_poids',
        'pouces_total',
        'qtt_cintrages',
        'dn',
        'ep',
        'matiere',
        'categorie',
        'dp_armement',
        'temps_fabrication',
        'temps_montage_total_estime',
        'temps_soudure_estime',
        'temps_montage_estime',
        'matiere_commande_le',
        'appro_matiere',
        'piking',
        'fin_debit',
        'debut_fabrication',
        'tuyauteur',
        'fin_assemblage',
        'nbr_soudure',
        'soudeur',
        'fin_soudage',
        'fin_fabrication',
        'depart_traitement',
        'retour_traitement',
        'livraison_bord',
        'debut_montage',
        'monte',
        'soude',
        'supporte',
        'nb_heures_montages',
        'equipe_montage',
        'nb_heures_soudages',
        'soudeurs',
        'temps_montage_total_reel',
        'eprouve_le',
        'non_conformite',
        'ordre',
    ];

    protected $casts = [
        'date_reception_iso' => 'date',
        'matiere_commande_le' => 'date',
        'appro_matiere' => 'date',
        'piking' => 'date',
        'fin_debit' => 'date',
        'debut_fabrication' => 'date',
        'fin_assemblage' => 'date',
        'fin_soudage' => 'date',
        'fin_fabrication' => 'date',
        'depart_traitement' => 'date',
        'retour_traitement' => 'date',
        'livraison_bord' => 'date',
        'debut_montage' => 'date',
        'monte' => 'date',
        'soude' => 'date',
        'supporte' => 'date',
        'eprouve_le' => 'date',
        'longueur_poids' => 'decimal:2',
        'pouces_total' => 'decimal:2',
        'temps_fabrication' => 'decimal:2',
        'temps_montage_total_estime' => 'decimal:2',
        'temps_soudure_estime' => 'decimal:2',
        'temps_montage_estime' => 'decimal:2',
        'nb_heures_montages' => 'decimal:2',
        'nb_heures_soudages' => 'decimal:2',
        'temps_montage_total_reel' => 'decimal:2',
    ];

    /**
     * Relation vers l'affaire
     */
    public function affaire(): BelongsTo
    {
        return $this->belongsTo(Affaire::class);
    }

    /**
     * Calcul de la différence temps de montage
     */
    public function getDiffTempsMontageAttribute(): ?float
    {
        if ($this->temps_montage_total_reel !== null && $this->temps_montage_total_estime !== null) {
            return round($this->temps_montage_total_reel - $this->temps_montage_total_estime, 2);
        }
        return null;
    }

    /**
     * Calcul de la différence temps de soudure
     */
    public function getDiffTempsSoudureAttribute(): ?float
    {
        if ($this->nb_heures_soudages !== null && $this->temps_soudure_estime !== null) {
            return round($this->nb_heures_soudages - $this->temps_soudure_estime, 2);
        }
        return null;
    }
}

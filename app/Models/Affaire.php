<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;
use Auth;

class Affaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'total_ht',
        'budget',
        'budget_notified',
        'statut',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'description',
        'created_at',
        'updated_at',
    ];

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_TERMINE = 'termine';
    const STATUT_ARCHIVE = 'archive';

    public static function getStatuts()
    {
        return [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_EN_COURS => 'En cours',
            self::STATUT_TERMINE => 'Terminé',
            self::STATUT_ARCHIVE => 'Archivé',
        ];
    }

    public function getStatutLabelAttribute()
    {
        return self::getStatuts()[$this->statut] ?? $this->statut;
    }

    public function getStatutColorAttribute()
    {
        return match($this->statut) {
            self::STATUT_EN_ATTENTE => 'gray',
            self::STATUT_EN_COURS => 'blue',
            self::STATUT_TERMINE => 'green',
            self::STATUT_ARCHIVE => 'red',
            default => 'gray',
        };
    }

    protected static function booted()
    {
        static::updating(function ($affaire) {
            if ($affaire->isDirty('statut') && ($affaire->statut === self::STATUT_TERMINE || $affaire->statut === self::STATUT_ARCHIVE)) {
                // Désassigner tous les matériels actifs
                foreach ($affaire->materiels as $materiel) {
                    if ($materiel->pivot->statut !== 'termine') {
                        $affaire->materiels()->updateExistingPivot($materiel->id, [
                            'date_fin' => now(),
                            'statut' => 'termine'
                        ]);
                    }
                }
            }
        });
    }

    /**
     * Relation avec les commandes (Cde)
     */
    public function cdes()
    {
        return $this->hasMany(Cde::class, 'affaire_id');
    }

    /**
     * Relation avec les demandes de prix (Ddp)
     */
    public function ddps()
    {
        return $this->hasMany(Ddp::class, 'affaire_id');
    }

    /**
     * Relation avec les réparations
     */
    public function reparations()
    {
        return $this->hasMany(Reparation::class, 'affaire_id');
    }

    /**
     * Relation avec les devis de tuyauterie
     */
    public function devisTuyauteries()
    {
        return $this->hasMany(DevisTuyauterie::class, 'affaire_id');
    }

    /**
     * Relation avec les dossiers de devis
     */
    public function dossiersDevis()
    {
        return $this->hasMany(DossierDevis::class, 'affaire_id');
    }

    /**
     * Relation avec les lignes de suivi tuyauterie
     */
    public function suiviLignes()
    {
        return $this->hasMany(AffaireSuiviLigne::class, 'affaire_id')->orderBy('ordre');
    }

    /**
     * Relation avec le matériel (Many-to-Many via affaire_materiel)
     */
    public function materiels()
    {
        return $this->belongsToMany(Materiel::class, 'affaire_materiel')
                    ->withPivot('date_debut', 'date_fin', 'statut')
                    ->withTimestamps();
    }

    /**
     * Relation avec le personnel (Many-to-Many via affaire_personnel)
     */
    public function personnels()
    {
        return $this->belongsToMany(Personnel::class, 'affaire_personnel')
                    ->using(AffairePersonnel::class)
                    ->withPivot('id', 'role', 'date_debut', 'date_fin', 'notes')
                    ->withTimestamps();
    }

    public function updateTotal()
    {
        // Calculer le total des commandes (sauf annulées et en attente)
        // 2 = En cours, 3 = Terminée, 5 = Vérifiée
        $totalCdes = $this->cdes->whereIn('ddp_cde_statut_id', [2, 3, 5])->sum('total_ht');

        // Calculer le total des réparations
        $totalReparations = Facture::whereIn('reparation_id', $this->reparations()->pluck('id'))->sum('montant_total');

        $this->total_ht = $totalCdes + $totalReparations;

        if ($this->total_ht <= 0 || is_null($this->total_ht)) {
            $this->total_ht = 0;
        }

        if ($this->budget && $this->total_ht > $this->budget && !$this->budget_notified) {
            try {
                Notification::createNotification(
                    Auth::user()->role,
                    'system',
                    'Budget dépassé',
                    "Le total HT ({$this->total_ht}) dépasse le budget ({$this->budget}) de l'affaire {$this->code}.",
                    'Vérifier le budget',
                    'affaires.show',
                    ['affaire' => $this->id],
                    'aller voir'
                );
                $this->budget_notified = true; // Marquer comme notifié
            } catch (\Exception $e) {
                // Log l'erreur ou gérer selon le besoin
                \Log::error('Erreur lors de la création de la notification : ' . $e->getMessage());
            }
        } else {
            $this->budget_notified = false; // Réinitialiser si le budget n'est pas dépassé
        }

        // Sauvegarder les modifications
        $this->save();
    }

    public static function generateNextCode()
    {
        $year = date('y');
        $lastAffaire = self::where('code', 'like', $year . '-%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastAffaire) {
            $parts = explode('-', $lastAffaire->code);
            if (count($parts) == 2 && is_numeric($parts[1])) {
                $sequence = intval($parts[1]) + 1;
            } else {
                $sequence = 1;
            }
        } else {
            $sequence = 1;
        }

        return $year . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}

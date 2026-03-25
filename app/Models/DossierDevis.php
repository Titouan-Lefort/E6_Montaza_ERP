<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierDevis extends Model
{
    use HasFactory;

    protected $table = 'dossiers_devis';

    protected $fillable = [
        'code',
        'nom',
        'affaire_id',
        'societe_id',
        'societe_contact_id',
        'reference_projet',
        'lieu_intervention',
        'description',
        'date_creation',
        'statut',
        'created_by',
    ];

    protected $casts = [
        'date_creation' => 'date',
    ];

    const STATUT_QUANTITATIF = 'quantitatif';
    const STATUT_EN_DEVIS = 'en_devis';
    const STATUT_VALIDE = 'valide';
    const STATUT_ARCHIVE = 'archive';

    /**
     * Génère le prochain code disponible
     */
    public static function generateNextCode(): string
    {
        $year = date('Y');
        $prefix = 'DD-' . $year . '-';

        $lastDossier = self::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastDossier) {
            $lastNumber = (int) substr($lastDossier->code, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relations
     */
    public function affaire()
    {
        return $this->belongsTo(Affaire::class);
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function societeContact()
    {
        return $this->belongsTo(SocieteContact::class);
    }

    public function quantitatifs()
    {
        return $this->hasMany(DossierDevisQuantitatif::class)->orderBy('ordre');
    }

    public function devisTuyauteries()
    {
        return $this->hasMany(DevisTuyauterie::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

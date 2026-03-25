<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Materiel extends Model
{
    use HasFactory;

    protected $table = 'materiels';

    protected $fillable = [
        'reference',
        'designation',
        'description',
        'numero_serie',
        'status',
        'acquisition_date',
    ];

    protected static function booted()
    {
        static::saving(function ($materiel) {
            // Si le matériel est marqué comme désactivé, forcer le statut à 'Inactif'
            if ($materiel->desactive) {
                $materiel->status = 'inactif';
            }
            // Sinon, si le statut est vide, le mettre par défaut à 'actif'
            elseif (empty($materiel->status)) {
                $materiel->status = 'actif';
            }
            // Sinon, garder le statut personnalisé (ex: 'maintenance', 'inactif' pour réparation)
        });
    }


    /**
     * Casts
     *
     * @var array<string,string>
     */
    protected $casts = [
        'acquisition_date' => 'date',
    ];

    /**
     * Accessor alias for 'référence' column as ->code
     */
    public function getCodeAttribute()
    {
        return $this->attributes['reference'] ?? null;
    }

    /**
     * Mutator alias to set 'référence' when assigning ->code
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['reference'] = $value;
    }

    /**
     * Accessor alias for 'numéro_série' column as ->numero_serie
     */
    public function getNumeroSerieAttribute()
    {
        return $this->attributes['numero_serie'] ?? null;
    }

    /**
     * Mutator alias to set 'numéro_série' when assigning ->numero_serie
     */
    public function setNumeroSerieAttribute($value)
    {
        $this->attributes['numero_serie'] = $value;
    }

    public function affaires()
    {
        return $this->belongsToMany(Affaire::class, 'affaire_materiel')
                    ->withPivot('date_debut', 'date_fin', 'statut')
                    ->withTimestamps();
    }
}

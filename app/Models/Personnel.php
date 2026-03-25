<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'personnels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'email',
        'telephone',
        'telephone_mobile',
        'poste',
        'departement',
        'date_embauche',
        'date_depart',
        'raison_depart',
        'motif_depart',
        'salaire',
        'adresse',
        'ville',
        'code_postal',
        'numero_securite_sociale',
        'statut',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_embauche' => 'date',
        'date_depart' => 'date',
        'salaire' => 'decimal:2',
    ];

    /**
     * Relation avec les affaires (Many-to-Many via affaire_personnel)
     */
    public function affaires()
    {
        return $this->belongsToMany(Affaire::class, 'affaire_personnel')
                    ->using(AffairePersonnel::class)
                    ->withPivot('id', 'role', 'date_debut', 'date_fin', 'notes')
                    ->withTimestamps();
    }

    /**
     * Relation avec les congés
     */
    public function conges()
    {
        return $this->hasMany(PersonnelConge::class);
    }

    /**
     * Vérifie si le personnel est actuellement en congé validé
     *
     * @param \Carbon\Carbon|null $date
     * @return bool
     */
    public function estEnConge($date = null)
    {
        $date = $date ?? \Carbon\Carbon::today();

        return $this->conges()
            ->where('statut', 'valide')
            ->where('date_debut', '<=', $date)
            ->where('date_fin', '>=', $date)
            ->exists();
    }

    /**
     * Retourne le congé actif du personnel s'il existe
     *
     * @param \Carbon\Carbon|null $date
     * @return PersonnelConge|null
     */
    public function congeActif($date = null)
    {
        $date = $date ?? \Carbon\Carbon::today();

        return $this->conges()
            ->where('statut', 'valide')
            ->where('date_debut', '<=', $date)
            ->where('date_fin', '>=', $date)
            ->first();
    }
}

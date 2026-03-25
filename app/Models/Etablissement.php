<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SocieteContact;
use Database\Factories\EtablissementFactory;

/**
 * Summary of Etablissement
 */
class Etablissement extends Model
{
    /** @use HasFactory<EtablissementFactory> */
    use HasFactory;
    protected $fillable = [
        'adresse',
        'complement_adresse',
        'nom',
        'code_postal',
        'ville',
        'region',
        'pay_id',
        'societe_id',
        'siret',
        'commentaire_id'
    ];


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('orderByName', function ($query) {
            $query->orderBy('nom');
        });
    }
    /**
     * Summary of pays
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Pays, Etablissement>
     */
    public function pays(): BelongsTo
    {
        /** @var BelongsTo<Pays, Etablissement> */
        return $this->belongsTo(Pays::class, 'pay_id');
    }
    /**
     * Summary of societe
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<SocieteContact, Etablissement>
     */
    public function societeContacts(): HasMany
    {
        /** @var HasMany<SocieteContact, Etablissement> */
        return $this->hasMany(SocieteContact::class);
    }
    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class);
    }
    public function hasCommentaire(): bool
    {
        return $this->commentaire()->exists();
    }
    public function societe(): BelongsTo
    {
        return $this->belongsTo(Societe::class);
    }
    public function contacts(): HasMany
    {
        return $this->hasMany(SocieteContact::class);
    }

    public function societeMatieres(): HasMany
    {
        return $this->hasMany(SocieteMatiere::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'societe_matieres')
            ->withPivot(['ref_externe', 'standard_version_id'])
            ->withTimestamps();
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\FormeJuridique;
use App\Models\CodeApe;
use App\Models\SocieteType;
use App\Models\Etablissement;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Commentaire;
use App\Models\Matiere;
use App\Models\ModelChange;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;


class Societe extends Model
{
    /** @use HasFactory<SocieteFactory> */
    use HasFactory;
    protected $fillable = [
        'raison_sociale',
        'siren',
        'forme_juridique_id',
        'code_ape_id',
        'numero_tva',
        'telephone',
        'email',
        'societe_type_id',
        'condition_paiement_id',
        'site_web',
        'commentaire_id'
    ];

    /**
     * Scope a query to only include suppliers (type 2 or 3).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFournisseurs($query)
    {
        return $query->whereIn('societe_type_id', [2, 3]);
    }
    /**
     * Scope a query to only include customers (type 1).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClients($query)
    {
        return $query->whereIn('societe_type_id', [1, 3]);
    }

    public function formeJuridique(): BelongsTo
    {
        return $this->belongsTo(FormeJuridique::class);
    }
    public function codeApe(): BelongsTo
    {
        return $this->belongsTo(CodeApe::class);
    }
    public function societeType(): BelongsTo
    {
        return $this->belongsTo(SocieteType::class, 'societe_type_id');
    }
    public function etablissements(): HasMany
    {
        return $this->hasMany(Etablissement::class);
    }
    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class);
    }
    public function hasCommentaire(): bool
    {
        return $this->commentaire()->exists();
    }
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'societe_matieres')
            ->withPivot(['ref_fournisseur', 'designation_fournisseur', 'prix', 'date_dernier_prix'])
            ->withTimestamps();
    }
    public function societeContacts(): HasManyThrough
    {
        return $this->hasManyThrough(SocieteContact::class, Etablissement::class);
    }
    public function conditionPaiement(): BelongsTo
    {
        return $this->belongsTo(ConditionPaiement::class);
    }
    protected static function booted(): void
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model): void {
            self::logChange($model, 'creating');
        });

        // Enregistrer avant la mise à jour d'un modèle
        static::updating(function ($model): void {
            if ($model->isDirty('remember_token')) {
                return;
            }
            self::logChange($model, 'updating');
        });

        // Enregistrer avant la suppression d'un modèle
        static::deleting(function ($model): void {
            self::logChange($model, 'deleting');
        });
    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'societe',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

}

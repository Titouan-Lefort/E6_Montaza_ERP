<?php

namespace App\Models;

use App\Models\MediaableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Cde extends Model
{
    /** @use HasFactory<\Database\Factories\CdeFactory> */
    use HasFactory;
    use MediaableTrait;

    protected $fillable = [
        'id',
        'code',
        'nom',
        'ddp_cde_statut_id',
        'old_statut',
        'user_id',
        'entite_id',
        'ddp_id',
        'affaire_id',
        'devis_numero',
        'affaire_suivi_par_id',
        'acheteur_id',
        'frais_de_port',
        'frais_divers',
        'frais_divers_texte',
        'total_ht',
        'tva',
        'total_ttc',
        'type_expedition_id',
        'adresse_livraison',
        'adresse_facturation',
        'condition_paiement_id',
        'accuse_reception',
        'show_ref_fournisseur',
        'afficher_destinataire',
        'commentaire_id',
        'custom_note',
        'changement_livraison',
        'created_at',
        'updated_at',
    ];
    protected static function booted()
    {
        static::saved(function ($cde) {
            if ($cde->affaire) {
                $cde->affaire->updateTotal();
            }
        });

        static::updated(function ($cde) {
            // 3 = Terminée (Livrée)
            if ($cde->ddp_cde_statut_id == 3) {
                $stockService = app(\App\Services\StockService::class);

                foreach ($cde->cdeLignes as $ligne) {
                    if (!$ligne->is_stocke && $ligne->matiere_id && $ligne->date_livraison_reelle) {
                        try {
                            $stockService->stock(
                                $ligne->matiere_id,
                                'entree',
                                (float) $ligne->quantite,
                                (float) $ligne->prix_unitaire,
                                'Livraison commande - ' . $cde->code,
                                $ligne->id
                            );

                            $ligne->is_stocke = true;
                            $ligne->saveQuietly();
                        } catch (\Exception $e) {
                            \Log::error("Failed to auto-stock line {$ligne->id}: " . $e->getMessage());
                        }
                    }
                }
            }
        });

        static::deleted(function ($cde) {
            if ($cde->affaire) {
                $cde->affaire->updateTotal();
            }
        });
    }

    public function cdeLignes()
    {
        return $this->hasMany(CdeLigne::class)->orderBy('poste');
    }
    public function ddpCdeStatut(): BelongsTo
    {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }
    public function statut(): BelongsTo
    {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function entite(): BelongsTo
    {
        return $this->belongsTo(Entite::class);
    }
    public function ddp(): BelongsTo
    {
        return $this->belongsTo(Ddp::class);
    }
    public function societeContacts(): HasManyThrough
    {
        return $this->hasManyThrough(
            SocieteContact::class,
            CdeSocieteContact::class,
            'cde_id',            // Foreign key on CdeSocieteContact that references Cde
            'id',                // Foreign key on SocieteContact that references SocieteContact
            'id',                // Local key on Cde
            'societe_contact_id' // Local key on CdeSocieteContact
        );
    }
    public function hasSocieteContact(): bool
    {
        return $this->societeContacts()->exists();
    }
    public function getEtablissementAttribute()
    {
        // Get the first societe contact's etablissement
        $societeContact = $this->societeContacts()->first();
        return $societeContact ? $societeContact->etablissement : null;
    }

    public function getSocieteAttribute()
    {
        // Get the first societe contact's societe
        $societeContact = $this->societeContacts()->first();
        return $societeContact?->etablissement?->societe;
    }
    public function affaireSuiviPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affaire_suivi_par_id');
    }
    public function acheteur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function typeExpedition(): BelongsTo
    {
        return $this->belongsTo(TypeExpedition::class);
    }
    public function conditionPaiement(): BelongsTo
    {
        return $this->belongsTo(ConditionPaiement::class);
    }
    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class);
    }
    public function cdeNotes()
    {
        return $this->belongsToMany(CdeNote::class, 'cde_cde_notes', 'cde_id', 'cde_note_id');
    }

    public function mouvementsStock(): HasManyThrough
    {
        return $this->hasManyThrough(
            MouvementStock::class,
            CdeLigne::class,
            'cde_id',        // Foreign key on CdeLigne that references Cde
            'cde_ligne_id',  // Foreign key on MouvementStock that references CdeLigne
            'id',            // Local key on Cde
            'id'             // Local key on CdeLigne
        );
    }

    public function affaire(): BelongsTo
    {
        return $this->belongsTo(Affaire::class,);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reparation extends Model
{
    use HasFactory;

    protected $table = 'reparations';

    protected $fillable = [
        'user_id',
        'materiel_id',
        'affaire_id',
        'description',
        'status',
    ];

    /**
     * Relation : reparation belongs to a user
     *
     * @return BelongsTo<User, Reparation>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : reparation belongs to a materiel
     *
     * @return BelongsTo<Materiel, Reparation>
     */
    public function materiel(): BelongsTo
    {
        return $this->belongsTo(Materiel::class);
    }

    public function affaire(): BelongsTo
    {
        return $this->belongsTo(Affaire::class);
    }

    /**
     * Relation : reparation has many factures
     *
     * @return HasMany<Facture>
     */
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }
}

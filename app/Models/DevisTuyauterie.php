<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisTuyauterie extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date_emission' => 'date',
        'options' => 'array',
        'is_archived' => 'boolean',
    ];

    public function sections()
    {
        return $this->hasMany(DevisTuyauterieSection::class);
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function societeContact()
    {
        return $this->belongsTo(SocieteContact::class);
    }

    public function affaire()
    {
        return $this->belongsTo(Affaire::class);
    }

    public function dossierDevis()
    {
        return $this->belongsTo(DossierDevis::class);
    }

    public function stockReservations()
    {
        return $this->hasMany(DevisStockReservation::class);
    }
}

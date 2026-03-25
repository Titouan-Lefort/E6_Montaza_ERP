<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocieteMatiere extends Model
{
    protected $fillable = [
        'matiere_id',
        'societe_id',
        'etablissement_id',
        'ref_externe',
        'standard_version_id',
    ];
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }
    public function prix()
    {
        return $this->hasMany(SocieteMatierePrix::class);
    }
    public function standardVersion()
    {
        return $this->belongsTo(StandardVersion::class);
    }
    public function getLastPrice()
    {
        return $this->prix()->latest()->first();
    }
}

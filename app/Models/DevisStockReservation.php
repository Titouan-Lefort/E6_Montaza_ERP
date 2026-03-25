<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisStockReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'devis_tuyauterie_id',
        'matiere_id',
        'quantite_reservee',
        'user_id',
        'statut',
        'notes',
    ];

    public function devisTuyauterie()
    {
        return $this->belongsTo(DevisTuyauterie::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelConge extends Model
{
    use HasFactory;
    protected $fillable = [
        'personnel_id',
        'date_debut',
        'date_fin',
        'type',
        'motif',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}

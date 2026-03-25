<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DdpCdeStatut extends Model
{
    /** @use HasFactory<\Database\Factories\DdpCdeStatutFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'couleur', 'couleur_texte'];
}

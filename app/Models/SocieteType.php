<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocieteType extends Model
{
    /** @use HasFactory<\Database\Factories\SocieteTypeFactory> */
    use HasFactory;
    protected $fillable = ['nom'];
    public function societes(): HasMany
    {
        return $this->hasMany(Societe::class);
    }
}

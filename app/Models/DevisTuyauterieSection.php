<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisTuyauterieSection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function lignes()
    {
        return $this->hasMany(DevisTuyauterieLigne::class);
    }
}

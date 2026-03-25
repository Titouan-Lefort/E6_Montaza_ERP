<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandardVersion extends Model
{
    protected $fillable = ['standard_id', 'version','chemin_pdf'];

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }
    public function path(): string
    {
        return $this->chemin_pdf;
    }
}

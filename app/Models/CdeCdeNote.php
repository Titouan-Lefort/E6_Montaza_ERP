<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CdeCdeNote extends Model
{
    protected $fillable = [
        'cde_id',
        'cde_note_id',
    ];

    public function cde()
    {
        return $this->belongsTo(Cde::class, 'cde_id');
    }

    public function cdeNote()
    {
        return $this->belongsTo(CdeNote::class, 'cde_note_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CdeSocieteContact extends Model
{
    protected $table = 'cde_societe_contacts';


    protected $fillable = [
        'societe_contact_id',
        'cde_id',
    ];

    public function societeContact()
    {
        return $this->belongsTo(SocieteContact::class, 'societe_contact_id');
    }

    public function cde()
    {
        return $this->belongsTo(Cde::class, 'cde_id');
    }
}

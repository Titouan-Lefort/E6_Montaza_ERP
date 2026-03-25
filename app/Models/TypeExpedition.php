<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeExpedition extends Model
{
    /** @use HasFactory<\Database\Factories\TypeExpeditionFactory> */
    use HasFactory;

    protected $fillable = ['nom','short'];
}

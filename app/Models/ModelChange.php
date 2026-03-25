<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelChange extends Model
{
    /**@use */
    // use HasFactory;

    protected $fillable = [
        'user_id',
        'model_type',
        'before',
        'after',
        'event',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    /**
     * Summary of user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User,ModelChange>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<\App\Models\User,ModelChange> */
        return $this->belongsTo(User::class);
    }
}

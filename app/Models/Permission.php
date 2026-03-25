<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    /** @use HasFactory<PermissionFactory> */
    use HasFactory;

    protected static function booted()
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model): void {
            self::logChange($model, 'creating');
        });

        // Enregistrer avant la mise à jour d'un modèle
        static::updating(function ($model): void {
            self::logChange($model, 'updating');
        });

        // Enregistrer avant la suppression d'un modèle
        static::deleting(function ($model): void {
            self::logChange($model, 'deleting');
        });
    }

    protected static function logChange(Model $model,string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Permissions',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    // Relation avec les rôles

    /**
     * Summary of roles
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Role,Permission>

     */
    public function roles(): BelongsToMany
    {
        /** @var BelongsToMany<\App\Models\Role,Permission> */
        return $this->belongsToMany(Role::class);
    }
}

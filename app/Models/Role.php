<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    /** @use HasFactory<RoleFactory>  */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];
    protected static function booted(): void
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
            'model_type' => 'Postes',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    /**
     * Get all of the users for the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\User,Role>
     */
    public function users(): HasMany
    {
        /** @var HasMany<\App\Models\User,Role> */
        return $this->hasMany(User::class);
    }

    /**
     * Get the entite that owns the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Entite,Role>
     */
    public function entite(): BelongsTo
    {
        /** @var BelongsTo<\App\Models\Entite,Role> */
        return $this->belongsTo(Entite::class, 'entite_id');
    }

    public function getIdFromName(string $name): ?int
    {
        $role = $this->where('name', $name)->first();

        return $role ? $role->id : null;
    }
    /**
     * Summary of permissions
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Permission,Role>
     */
    public function permissions(): BelongsToMany
    {
        /** @var BelongsToMany<\App\Models\Permission,Role> */
        return $this->belongsToMany(Permission::class);
    }
    public function hasPermission(string $permission): bool
    {
        return $this->permissions->contains('name', $permission);
    }
    /**
     * Summary of hasPermissions
     * @param array<string> $permissions
     * @return bool
     */
    public function hasPermissions(array $permissions): bool
    {
        return $this->permissions->whereIn('name', $permissions)->count() === count($permissions);
    }
    /**
     * Summary of hasAnyPermission
     * @param array<string> $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions->whereIn('name', $permissions)->count() > 0;
    }
    /**
     * Summary of notifications
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Notification,Role>
     */
    public function notifications(): HasMany
    {
        /** @var HasMany<\App\Models\Notification,Role> */
        return $this->hasMany(Notification::class);
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory>  */
    use HasFactory, Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'id',
        'last_name',
        'first_name',
        'phone',
        'email',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected static function booted(): void
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model): void {
            self::logChange($model, 'creating');
        });

        // Enregistrer avant la mise à jour d'un modèle
        static::updating(function ($model): void {
            if ($model->isDirty('remember_token')) {
                return;
            }
            self::logChange($model, 'updating');
        });

        // Enregistrer avant la suppression d'un modèle
        static::deleting(function ($model): void {
            self::logChange($model, 'deleting');
        });
    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Utilisateurs',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

    /**
     * Summary of hasRole
     * @param int $role_id
     * @return bool
     */
    public function hasRole(int $role_id): bool
    {
        return $this->role_id === $role_id;
    }

    /**
     * Summary of role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Role, \App\Models\User>
     */
    public function role(): BelongsTo
    {
        /** @var BelongsTo<\App\Models\Role, \App\Models\User> */
        return $this->belongsTo(related: Role::class, foreignKey: 'role_id');
    }

    /**
     * Summary of permissions
     * @return Collection<int, Permission>
     */
    public function permissions(): Collection
    {
        /** @var Collection<int, Permission> */
        return $this->role->permissions ?? collect(); // Si pas de rôle, renvoie une collection vide
    }

    /**
     * Check if the user has a specific permission.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->contains('name', $permission);
    }
    /**
     * Get the notifications for the user.
     *
     * @return HasMany<Notification, User>
     */
    public function notifications(): HasMany
    {
        if ($this->role) {
            /** @var HasMany<Notification, User> */
            return $this->role->hasMany(Notification::class);
        }

        // Retourne une relation HasMany vide si le rôle est nul
        /** @var HasMany<Notification, User> */
        return $this->hasMany(Notification::class)->whereRaw('0 = 1');
    }


    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the role name of the user.
     *
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->role->name ?? '';
    }
    /**
     * Summary of getRole
     * @return \App\Models\Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * Get the first name of the user.
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * Get the last name of the user.
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }
    public function shortcuts()
    {
        return $this->hasMany(UserShortcut::class) ?? collect();
    }
}

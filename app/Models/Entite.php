<?php

namespace App\Models;

use Database\Factories\EntiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Role;
use Auth;

class Entite extends Model
{
    /** @use HasFactory<EntiteFactory>  */
    use HasFactory;

    protected $fillable = [
        'name',
        'adresse',
        'ville',
        'code_postal',
        'tel',
        'siret',
        'rcs',
        'numero_tva',
        'code_ape',
        'logo',
        'horaires',
    ];



    protected static function booted(): void
    {
        static::created(function ($model): void {
            self::logChange($model, 'creating');
        });

        static::updating(function ($model): void {
            self::logChange($model, 'updating');
        });

        static::deleting(function ($model): void {
            self::logChange($model, 'deleting');
        });

    }

    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Entite',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    /**
     * @return HasMany<Role, Entite>
     */
    public function roles(): HasMany
    {
        /** @var HasMany<Role, Entite> */
        return $this->hasMany(Role::class);
    }
}

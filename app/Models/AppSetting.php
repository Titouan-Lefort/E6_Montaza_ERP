<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class AppSetting extends Model
{
    protected $fillable = [
        'mail_from_address',
        'mail_from_name',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
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
            'model_type' => 'AppSetting',
            'before' => collect($model->getOriginal())->map(function ($value, $key) {
                return $key === 'mail_password' ? 'Ancien mot de passe' : $value;
            }),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    // Encrypt the password before saving
    public function setMailPasswordAttribute($value)
    {
        $this->attributes['mail_password'] = $value ? Crypt::encryptString($value) : null;
    }

    // Decrypt the password when retrieving
    public function getMailPasswordAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }
}

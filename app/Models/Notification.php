<?php

namespace App\Models;

use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    /** @use HasFactory<NotificationFactory>  */
    use HasFactory;
    use SoftDeletes;
    /**
     * Summary of createNotification
     * @param \App\Models\Role $role
     * @param string $type
     * @param string $title
     * @param string $message
     * @param string $action_requise
     * @param string $route_nom
     * @param array<string, mixed> $route_data
     * @param string $label
     * @return \App\Models\Notification
     */
    public static function createNotification(Role $role, string $type, string $title, ?string $message = null, ?string $action_requise = null, ?string $route_nom = null,?array $route_data = null, string $label = 'aller voir'): Notification
    {
        $notification = new self();
        $notification->role_id = $role->id;
        $notification->type = $type;
        $data = [
            'title' => $title,
        ];

        if ($message !== null) {
            $data['message'] = $message;
        }

        if ($action_requise !== null) {
            $data['action_requise'] = $action_requise;
        }

        if ($route_nom !== null && $route_data !== null && $label !== null && $action_requise !== null) {
            $data['action'] = [
                'route_nom' => $route_nom,
                'route_data' => $route_data,
                'label' => $label
            ];
        }
        $notification->data = json_encode($data) ?: '';
        $notification->read = false;
        $notification->save();
        return $notification;
    }

    protected $fillable = [
        'role_id',
        'type',
        'data',
        'read',
    ];
    /**
     * Summary of role
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Role,Notification>
     */
    public function role(): BelongsTo
    {
        /** @var BelongsTo<\App\Models\Role,Notification> */
        return $this->belongsTo(Role::class);
    }
}

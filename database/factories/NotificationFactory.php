<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'role_id' => $this->faker->numberBetween(1, 7),
            'type' => 'system',
            'data' => json_encode([
                'title' => $this->faker->sentence(6,  true),
                'message' => $this->faker->paragraph,
                'action_requise' => $this->faker->randomElement(['Action requise', '']),
            ]),
            'read' => false,
            'created_at' => $this->faker->dateTimeThisYear,
        ];
    }
}

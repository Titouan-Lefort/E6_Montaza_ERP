<?php

namespace Database\Factories;

use App\Models\Reparation;
use App\Models\User;
use App\Models\Materiel;
use App\Models\Affaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReparationFactory extends Factory
{
    protected $model = Reparation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'materiel_id' => Materiel::inRandomOrder()->first()->id ?? Materiel::factory(),
            'description' => $this->faker->text(200),
            'status' => $this->faker->randomElement(['en_attente', 'en_cours', 'terminee']),
            'affaire_id' => Affaire::inRandomOrder()->first()->id ?? null,
        ];
    }
}

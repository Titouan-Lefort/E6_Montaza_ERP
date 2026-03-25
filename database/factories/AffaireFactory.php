<?php

namespace Database\Factories;

use App\Models\Affaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class AffaireFactory extends Factory
{
    protected $model = Affaire::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[2-3][0-9]-[0-9]{3}'), // Ex: 25-001
            'nom' => $this->faker->company() . ' - ' . $this->faker->words(2, true),
            'budget' => $this->faker->randomFloat(2, 5000, 100000),
            'statut' => $this->faker->randomElement([
                Affaire::STATUT_EN_ATTENTE,
                Affaire::STATUT_EN_COURS,
                Affaire::STATUT_TERMINE
            ]),
            'date_debut' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'date_fin_prevue' => $this->faker->dateTimeBetween('now', '+6 months'),
            'description' => $this->faker->paragraph(),
            'total_ht' => 0, // Sera recalculé
        ];
    }
}

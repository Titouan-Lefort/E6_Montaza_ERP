<?php

namespace Database\Factories;

use App\Models\Materiel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterielFactory extends Factory
{
    protected $model = Materiel::class;

    public function definition(): array
    {
        return [
            'reference' => 'MAT-' . $this->faker->unique()->numberBetween(1000, 9999),
            'designation' => $this->faker->randomElement(['Poste à souder', 'Meuleuse', 'Perceuse colonne', 'Groupe électrogène', 'Echafaudage', 'Caisse à outils']),
            'description' => $this->faker->sentence(),
            'numero_serie' => $this->faker->bothify('SN-####-????'),
            'status' => 'actif',
            'acquisition_date' => $this->faker->date(),
        ];
    }
}

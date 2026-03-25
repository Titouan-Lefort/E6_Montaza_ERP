<?php

namespace Database\Factories;

use App\Models\Facture;
use App\Models\Reparation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactureFactory extends Factory
{
    protected $model = Facture::class;

    public function definition(): array
    {
        return [
            'numero_facture' => 'FAC-' . $this->faker->unique()->bothify('####-????'),
            'date_emission' => $this->faker->date(),
            'montant_total' => $this->faker->randomFloat(2, 100, 5000),
            'reparation_id' => Reparation::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Ddp;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DdpLigne>
 */
class DdpLigneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $random_ddp = Ddp::all()->random();
        $random_matiere = Matiere::all()->random();
        return [
            'ddp_id' => $random_ddp->id,
            'matiere_id' => $random_matiere->id,
            'quantite' => $this->faker->numberBetween(1, 100),
        ];
    }
}

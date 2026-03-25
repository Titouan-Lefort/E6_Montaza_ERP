<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DevisTuyauterie>
 */
class DevisTuyauterieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference_projet' => $this->faker->bothify('PROJ-####'),
            'lieu_intervention' => $this->faker->address,
            'client_nom' => $this->faker->company,
            'client_contact' => $this->faker->name,
            'client_adresse' => $this->faker->address,
            'date_emission' => now(),
            'duree_validite' => 30,
            'conditions_paiement' => '30 jours',
            'delais_execution' => '4 semaines',
            'options' => [
                'nacelle' => true,
                'echafaudage' => false
            ],
            'total_ht' => 1000,
            'total_tva' => 200,
            'total_ttc' => 1200,
            'marge_globale' => 200,
            'is_archived' => false,
        ];
    }
}

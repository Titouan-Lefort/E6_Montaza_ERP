<?php

namespace Database\Factories;

use App\Models\DevisTuyauterieSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DevisTuyauterieLigne>
 */
class DevisTuyauterieLigneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'devis_tuyauterie_section_id' => DevisTuyauterieSection::factory(),
            'type' => 'fourniture',
            'designation' => $this->faker->words(3, true),
            'matiere' => 'Inox',
            'quantite' => 1,
            'unite' => 'u',
            'prix_achat' => 10,
            'prix_unitaire' => 20,
            'total_ht' => 20,
            'ordre' => 0,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\DevisTuyauterie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DevisTuyauterieSection>
 */
class DevisTuyauterieSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'devis_tuyauterie_id' => DevisTuyauterie::factory(),
            'titre' => $this->faker->sentence(3),
            'ordre' => 0,
        ];
    }
}

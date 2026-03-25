<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocieteContact>
 */
class SocieteContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if ($this->faker->boolean) {
            $etablissement = \App\Models\Etablissement::factory()->create();
        } else {
            if (!\App\Models\Etablissement::exists()) {
                $etablissement = \App\Models\Etablissement::factory()->create();
            } else {
                $etablissement = \App\Models\Etablissement::inRandomOrder()->first();
            }
        }
        return [

            'nom' => $this->faker->lastName(). ' ' . $this->faker->firstName(),
            'fonction' => $this->faker->jobTitle(),
            'email' => $this->faker->unique()->safeEmail(),
            'telephone_fixe' => '02' . $this->faker->numerify('########'),
            'telephone_portable' => '06' . $this->faker->numerify('########'),
            'etablissement_id' => $etablissement->id,
        ];
    }
}

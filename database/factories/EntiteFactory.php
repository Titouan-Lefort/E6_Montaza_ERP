<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entite>
 */
class EntiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'adresse' => $this->faker->address,
            'ville' => $this->faker->city,
            'code_postal' => $this->faker->postcode,
            'tel' => $this->faker->phoneNumber,
            'siret' => $this->faker->regexify('[0-9]{14}'),
            'rcs' => $this->faker->regexify('[A-Z]{2}[0-9]{9}'),
            'numero_tva' => $this->faker->regexify('FR[0-9]{2}[0-9]{9}'),
            'code_ape' => $this->faker->regexify('[0-9]{4}[A-Z]'),
            'logo' => $this->faker->optional()->imageUrl(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Commentaire;
use App\Models\ConditionPaiement;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Societe>
 */
class SocieteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $siren = $this->faker->numberBetween(100000000, 999999999);
        $raison_sociale = $this->faker->company;
        $random_condition_paiement = ConditionPaiement::all()->random()->id;
        return [
            'raison_sociale' => $raison_sociale,
            'siren' => $siren,
            'forme_juridique_id' => \App\Models\FormeJuridique::inRandomOrder()->first()->id,
            'code_ape_id' => \App\Models\CodeApe::inRandomOrder()->first()->id,
            'societe_type_id' => \App\Models\SocieteType::inRandomOrder()->first()->id,
            'telephone' => '02' . $this->faker->numerify('########'),
            'email' => 'contact@' . strtolower(str_replace(' ', '', $raison_sociale)) . '.fr',
            'site_web' => 'www.' . strtolower(str_replace(' ', '-', $raison_sociale)) . '.fr',
            'numero_tva' => 'FR' .$this->faker->numberBetween(10,99). $siren,
            'condition_paiement_id' => $random_condition_paiement,
            'commentaire_id' => Commentaire::factory()->create()->id,
        ];
    }
}

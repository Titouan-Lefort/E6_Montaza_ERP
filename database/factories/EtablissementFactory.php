<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Commentaire;
use App\Models\Pays;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etablissement>
 */
class EtablissementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if ($this->faker->boolean) {
            $societe = \App\Models\Societe::factory()->create();
        } else {
            if (!\App\Models\Societe::exists()) {
                $societe = \App\Models\Societe::factory()->create();
            } else {
                $societe = \App\Models\Societe::inRandomOrder()->first();
            }
        }
        return [

            'adresse' => $this->faker->buildingNumber . ' ' . $this->faker->streetName,
            'nom' => $this->faker->randomElement([
                'dépôt',
                'siège social',
                'magasin',
                'bureau',
                'entrepôt',
                'atelier',
                'usine',
                'centre de distribution',
                'showroom',
                'point de vente',
                'agence',
                'site de production',
                'site de stockage',
                'site de vente en ligne',
                'site de vente à distance',
                'site de vente par correspondance',
                'site de vente par téléphone',
                'site de vente par catalogue',
            ]),
            'code_postal' => $this->faker->postcode,
            'ville' => $this->faker->city,
            'region' => $this->faker->randomElement([
                'Île-de-France',
                'Provence-Alpes-Côte d\'Azur',
                'Auvergne-Rhône-Alpes',
                'Nouvelle-Aquitaine',
                'Occitanie',
                'Hauts-de-France',
                'Bretagne',
                'Grand Est',
                'Pays de la Loire',
                'Normandie',
            ]),
            'pay_id' => Pays::inRandomOrder()->first(),
            'societe_id' => $societe,
            'siret' => $societe->siren . $this->faker->numberBetween(10000, 99999),
            'commentaire_id' => Commentaire::factory()->create()->id,
        ];
    }
}

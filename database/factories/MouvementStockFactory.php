<?php

namespace Database\Factories;

use App\Models\CdeLigne;
use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MouvementStockFactory extends Factory
{
    protected $model = MouvementStock::class;

    public function definition()
    {
        return [
            'matiere_id' => Matiere::factory(),
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['entree', 'sortie']),
            'quantite' => $this->faker->randomFloat(2, 1, 1000),
            'valeur_unitaire' => $this->faker->randomFloat(2, 0.01, 500),
            'raison' => $this->faker->sentence(),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'cde_ligne_id' => null,
        ];
    }

    /**
     * Mouvement de type entrée
     */
    public function entree()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'entree',
            ];
        });
    }

    /**
     * Mouvement de type sortie
     */
    public function sortie()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'sortie',
            ];
        });
    }

    /**
     * Mouvement lié à une ligne de commande
     */
    public function pourCdeLigne(CdeLigne $ligne = null)
    {
        return $this->state(function (array $attributes) use ($ligne) {
            $cdeLigne = $ligne ?? CdeLigne::factory()->create();

            return [
                'cde_ligne_id' => $cdeLigne->id,
                'matiere_id' => $cdeLigne->matiere_id ?? Matiere::factory(),
                'type' => 'entree',
                'raison' => 'Livraison commande - ' . $cdeLigne->cde->code,
            ];
        });
    }
}

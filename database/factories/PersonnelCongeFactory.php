<?php

namespace Database\Factories;

use App\Models\Personnel;
use App\Models\PersonnelConge;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonnelCongeFactory extends Factory
{
    protected $model = PersonnelConge::class;

    public function definition()
    {
        $dateDebut = $this->faker->dateTimeBetween('-1 year', '+6 months');
        $dateFin = $this->faker->dateTimeBetween($dateDebut, '+1 month');

        return [
            'personnel_id' => Personnel::factory(),
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'type' => $this->faker->randomElement(['conge_paye', 'conge_maladie', 'conge_sans_solde', 'autre']),
            'motif' => $this->faker->optional()->sentence(),
            'statut' => $this->faker->randomElement(['demande', 'valide', 'refuse']),
        ];
    }

    /**
     * Congé payé
     */
    public function congePaye()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'conge_paye',
                'statut' => 'valide',
            ];
        });
    }

    /**
     * Congé maladie
     */
    public function congeMaladie()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'conge_maladie',
                'statut' => 'valide',
            ];
        });
    }

    /**
     * Congé en attente de validation
     */
    public function enDemande()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'demande',
            ];
        });
    }

    /**
     * Congé validé
     */
    public function valide()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'valide',
            ];
        });
    }

    /**
     * Congé refusé
     */
    public function refuse()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'refuse',
            ];
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonnelFactory extends Factory
{
    protected $model = Personnel::class;

    public function definition()
    {
        return [
            'matricule' => strtoupper($this->faker->unique()->bothify('EMP###')),
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->optional()->numerify('01########'),
            'telephone_mobile' => $this->faker->optional()->numerify('06########'),
            'poste' => $this->faker->randomElement(['Tuyauteur', 'Soudeur', 'Chef de chantier', 'Contrôleur qualité', 'Ingénieur', 'Technicien']),
            'departement' => $this->faker->randomElement(['Production', 'Qualité', 'Maintenance', 'Logistique', 'Administration', 'R&D']),
            'date_embauche' => $this->faker->optional()->dateTimeBetween('-10 years', 'now'),
            'date_depart' => null,
            'salaire' => $this->faker->optional()->randomFloat(2, 1800, 5000),
            'adresse' => $this->faker->optional()->streetAddress(),
            'ville' => $this->faker->optional()->city(),
            'code_postal' => $this->faker->optional()->postcode(),
            'numero_securite_sociale' => $this->faker->optional()->numerify('###############'),
            'statut' => $this->faker->randomElement(['actif', 'en_conge', 'suspendu', 'parti']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Personnel actif
     */
    public function actif()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'actif',
                'date_depart' => null,
            ];
        });
    }

    /**
     * Personnel en congé
     */
    public function enConge()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'en_conge',
            ];
        });
    }

    /**
     * Personnel parti
     */
    public function parti()
    {
        return $this->state(function (array $attributes) {
            return [
                'statut' => 'parti',
                'date_depart' => $this->faker->dateTimeBetween('-2 years', 'now'),
            ];
        });
    }

    /**
     * Personnel avec toutes les informations
     */
    public function complet()
    {
        return $this->state(function (array $attributes) {
            return [
                'telephone' => $this->faker->numerify('01########'),
                'telephone_mobile' => $this->faker->numerify('06########'),
                'date_embauche' => $this->faker->dateTimeBetween('-5 years', '-1 year'),
                'salaire' => $this->faker->randomFloat(2, 2000, 4500),
                'adresse' => $this->faker->streetAddress(),
                'ville' => $this->faker->city(),
                'code_postal' => $this->faker->postcode(),
                'numero_securite_sociale' => $this->faker->numerify('###############'),
                'notes' => $this->faker->paragraph(),
            ];
        });
    }
}

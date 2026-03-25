<?php

namespace Database\Factories;

use App\Models\Ddp;
use App\Models\DdpCdeStatut;
use App\Models\User;
use App\Models\Commentaire;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddp>
 */
class DdpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //samplecode : DDP25-0000
        $code = 'DDP-' . date('y'). '-' . $this->faker->numberBetween(1000, 9999);
        $random_statut = DdpCdeStatut::all()->random();
        $random_user = User::all()->random();

        // Créer un commentaire vide ou avec du texte aléatoire
        $commentaire = Commentaire::create(['contenu' => $this->faker->paragraph()]);

        return [
            'code' => $code,
            'nom' => $this->faker->sentence(3),
            'entite_id' => $this->faker->numberBetween(1, 3),
            'ddp_cde_statut_id' => $random_statut->id,
            'user_id' => $random_user->id,
            'commentaire_id' => $commentaire->id,
        ];
    }
}

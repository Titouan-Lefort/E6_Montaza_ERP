<?php

namespace Database\Seeders;

use App\Models\Affaire;
use App\Models\Materiel;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer 20 matériels
        $materiels = Materiel::factory()->count(20)->create();

        // Créer 10 affaires
        $affaires = Affaire::factory()->count(10)->create()->each(function ($affaire) use ($materiels) {
            // Associer aléatoirement 1 à 5 matériels à chaque affaire
            $affaire->materiels()->attach(
                $materiels->random(rand(1, 5))->pluck('id')->toArray(),
                [
                    'date_debut' => now(),
                    'statut' => 'reserve'
                ]
            );

            // Créer des commandes liées à l'affaire (quel que soit l'état)
            \App\Models\Cde::factory()->count(rand(1, 3))->create([
                'affaire_id' => $affaire->id,
            ]);
        });
    }
}

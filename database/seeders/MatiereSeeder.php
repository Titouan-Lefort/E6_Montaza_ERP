<?php
namespace Database\Seeders;

use App\Models\Matiere;
use App\Models\Societe;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MatiereSeeder extends Seeder
{
    public function run()
    {
        // Créer une instance de Faker
        $faker = Faker::create();
        // Filtrer les sociétés une seule fois
        $societes = Societe::whereIn('societe_type_id', [2, 3])->get();

        if ($societes->isEmpty()) {
            return;
        }

        // Créer des matières et lier les sociétés via la table intermédiaire
        Matiere::factory(100)->create()->each(function ($matiere) use ($societes, $faker) {
            // S'assurer de ne pas demander plus de sociétés qu'il n'y en a
            $max = min(10, $societes->count());
            $take = rand(1, $max);

            $matiere->fournisseurs()->attach(
                $societes->random($take)->pluck('id'),
                [
                    'ref_externe' => strtoupper($faker->lexify('??')) . '-' . $faker->numerify('####'),
                ]
            );
        });
    }
}



<?php

namespace Database\Seeders;

use App\Models\DdpCdeStatut;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DdpCdeStatutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuts = [
            ['nom' => 'En attente', 'couleur' => '#F4C27F', 'couleur_texte' => '#5A3E1B'],  // Marron foncé, bon contraste sur orange pastel
            ['nom' => 'En cours', 'couleur' => '#E57373', 'couleur_texte' => '#5A1B1B'],   // Rouge bordeaux foncé, contraste fort
            ['nom' => 'Terminée', 'couleur' => '#77DD77', 'couleur_texte' => '#145214'],   // Vert forêt foncé, contraste fort
            ['nom' => 'Annulée', 'couleur' => '#A9A9A9', 'couleur_texte' => '#333333'],    // Gris foncé, contraste neutre
            ['nom' => 'Vérifiée', 'couleur' => '#C27FF4', 'couleur_texte' => '#3E1B5A'],  // Violet foncé, contraste fort
        ];

        foreach ($statuts as $statut) {
            DdpCdeStatut::create($statut);
        }
    }
}

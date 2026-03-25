<?php

namespace Database\Seeders;

use App\Models\Entite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Entite::factory()->create([
            'name' => 'Atlantis Montaza',
            'adresse' => '1 Rue de la Cité Nouvelle - ZI Altitude',
            'ville' => 'Trignac',
            'code_postal' => '44570',
            'tel' => '02 40 17 65 62',
            'siret' => '48042671700074',
            'rcs' => 'SAINT NAZAIRE B 480426717',
            'numero_tva' => 'FR37 480 426 717',
            'code_ape' => '3320A',
            'logo' => '/img/atlantis-montaza.png',
            'horaires' => 'Du lundi au vendredi de 7h30 à 12h00 et de 13h00 à 15h30',
        ]);
        Entite::factory()->create([
            'name' => 'Atlantis Ventilation',
            'adresse' => '1 Rue de la Cité Nouvelle - ZI Altitude',
            'ville' => 'Trignac',
            'code_postal' => '44570',
            'tel' => '02 40 17 65 62',
            'siret' => '88514481600018',
            'rcs' => 'SAINT NAZAIRE B 885144816',
            'numero_tva' => 'FR96 885 144 816',
            'code_ape' => '4322B',
            'logo' => '/img/atlantis-ventilation.png',
            'horaires' => 'Du lundi au vendredi de 7h30 à 12h00 et de 13h00 à 15h30',
        ]);
        Entite::factory()->create([
            'name' => 'AMB Navale',
            'adresse' => '1 Rue de la Cité Nouvelle - ZI Altitude',
            'ville' => 'Trignac',
            'code_postal' => '44570',
            'tel' => '02 40 17 65 62',
            'siret' => '82797194600013',
            'rcs' => 'SAINT NAZAIRE B 827971946',
            'numero_tva' => 'FR87 827 971 946',
            'code_ape' => '3011Z',
            'logo' => '/img/amb-navale.png',
            'horaires' => 'Du lundi au vendredi de 7h30 à 12h00 et de 13h00 à 15h30',
        ]);
    }
}

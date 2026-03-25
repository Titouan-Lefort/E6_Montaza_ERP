<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unites = [
            ['short' => 'KG', 'full' => 'Kilogramme', 'full_plural' => 'Kilogrammes', 'type' => 'Poids'],
            ['short' => 'ML', 'full' => 'Mètre linéaire', 'full_plural' => 'Mètres linéaires', 'type' => 'Longueur'],
            ['short' => 'M²', 'full' => 'Mètre carré', 'full_plural' => 'Mètres carrés', 'type' => 'Surface'],
            ['short' => 'M³', 'full' => 'Mètre cube', 'full_plural' => 'Mètres cubes', 'type' => 'Volume'],
            ['short' => 'L', 'full' => 'Litre', 'full_plural' => 'Litres', 'type' => 'Volume'],
            ['short' => 'U', 'full' => 'Unité', 'full_plural' => 'Unités', 'type' => 'Unité'],
            ['short' => 'M', 'full' => 'Mètre', 'full_plural' => 'Mètres', 'type' => 'Longueur'],
            ['short' => 'CM', 'full' => 'Centimètre', 'full_plural' => 'Centimètres', 'type' => 'Longueur'],
            ['short' => 'MM', 'full' => 'Millimètre', 'full_plural' => 'Millimètres', 'type' => 'Longueur'],
            ['short' => 'G', 'full' => 'Gramme', 'full_plural' => 'Grammes', 'type' => 'Poids'],
            ['short' => 'MG', 'full' => 'Milligramme', 'full_plural' => 'Milligrammes', 'type' => 'Poids'],
            ['short' => 'S', 'full' => 'Seconde', 'full_plural' => 'Secondes', 'type' => 'Temps'],
            ['short' => 'MIN', 'full' => 'Minute', 'full_plural' => 'Minutes', 'type' => 'Temps'],
            ['short' => 'H', 'full' => 'Heure', 'full_plural' => 'Heures', 'type' => 'Temps'],
            ['short' => 'J', 'full' => 'Jour', 'full_plural' => 'Jours', 'type' => 'Temps'],
            ['short' => 'MOIS', 'full' => 'Mois', 'full_plural' => 'Mois', 'type' => 'Temps'],
            ['short' => 'AN', 'full' => 'Année', 'full_plural' => 'Années', 'type' => 'Temps'],
            ['short' => 'W', 'full' => 'Watt', 'full_plural' => 'Watts', 'type' => 'Puissance'],
            ['short' => 'T', 'full' => 'Tonne', 'full_plural' => 'Tonnes' , 'type' => 'Poids']
        ];

        foreach ($unites as $unite) {
            \App\Models\Unite::create($unite);
        }
    }
}

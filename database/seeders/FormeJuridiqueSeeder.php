<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormeJuridiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formesJuridiques = [
            ['code' => 'SARL', 'nom' => 'Société à Responsabilité Limitée'],
            ['code' => 'SAS', 'nom' => 'Société par Actions Simplifiée'],
            ['code' => 'EI', 'nom' => 'Entreprise Individuelle'],
            ['code' => 'EURL', 'nom' => 'Entreprise Unipersonnelle à Responsabilité Limitée'],
            ['code' => 'SA', 'nom' => 'Société Anonyme'],
            ['code' => 'SASU', 'nom' => 'Société par Actions Simplifiée Unipersonnelle'],
            ['code' => 'SCI', 'nom' => 'Société Civile Immobilière'],
            ['code' => 'SC', 'nom' => 'Société Civile'],
            ['code' => 'SNC', 'nom' => 'Société en Nom Collectif'],
            ['code' => 'SCOP', 'nom' => 'Société Coopérative de Production'],
            ['code' => 'SCS', 'nom' => 'Société en Commandite Simple'],
            ['code' => 'SCA', 'nom' => 'Société en Commandite par Actions'],
            ['code' => 'EIRL', 'nom' => 'Entreprise Individuelle à Responsabilité Limitée'],
            ['code' => 'SCM', 'nom' => 'Société Civile de Moyens'],
            ['code' => 'SCP', 'nom' => 'Société Civile Professionnelle'],
            ['code' => 'SELARL', 'nom' => 'Société d\'Exercice Libéral à Responsabilité Limitée'],
            ['code' => 'SELAS', 'nom' => 'Société d\'Exercice Libéral par Actions Simplifiée'],
            ['code' => 'SELCA', 'nom' => 'Société d\'Exercice Libéral en Commandite par Actions'],
            ['code' => 'SEL', 'nom' => 'Société d\'Exercice Libéral (général)'],
            ['code' => 'SE', 'nom' => 'Société Européenne'],
            ['code' => 'GIE', 'nom' => 'Groupement d\'Intérêt Économique'],
            ['code' => 'GE', 'nom' => 'Groupement d\'Employeurs'],
            ['code' => 'SPFPL', 'nom' => 'Société de Participations Financières de Professions Libérales'],
            ['code' => 'SASP', 'nom' => 'Société Anonyme Sportive Professionnelle'],
            ['code' => 'EARL', 'nom' => 'Exploitation Agricole à Responsabilité Limitée'],
            ['code' => 'GAEC', 'nom' => 'Groupement Agricole d\'Exploitation en Commun'],
            ['code' => 'SCEA', 'nom' => 'Société Civile d\'Exploitation Agricole'],
            ['code' => 'AE', 'nom' => 'Auto-entrepreneur (Micro-entreprise)'],
        ];

        DB::table('forme_juridiques')->insert($formesJuridiques);
    }
}

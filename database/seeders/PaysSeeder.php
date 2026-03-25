<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            'Afghanistan', 'Afrique du Sud', 'Albanie', 'Algérie', 'Allemagne', 'Andorre', 'Angola',
            'Antigua-et-Barbuda', 'Arabie Saoudite', 'Argentine', 'Arménie', 'Australie', 'Autriche',
            'Azerbaïdjan', 'Bahamas', 'Bahreïn', 'Bangladesh', 'Barbade', 'Belgique', 'Belize', 'Bénin',
            'Bhoutan', 'Biélorussie', 'Birmanie (Myanmar)', 'Bolivie', 'Bosnie-Herzégovine', 'Botswana',
            'Brésil', 'Brunei', 'Bulgarie', 'Burkina Faso', 'Burundi', 'Cambodge', 'Cameroun', 'Canada',
            'Cap-Vert', 'Centrafrique', 'Chili', 'Chine', 'Chypre', 'Colombie', 'Comores', 'Congo-Brazzaville',
            'Congo-Kinshasa (République Démocratique du Congo)', 'Corée du Nord', 'Corée du Sud', 'Costa Rica',
            'Côte d\'Ivoire', 'Croatie', 'Cuba', 'Danemark', 'Djibouti', 'Dominique', 'Égypte', 'Émirats Arabes Unis',
            'Équateur', 'Érythrée', 'Espagne', 'Estonie', 'États-Unis', 'Eswatini (Swaziland)', 'Éthiopie', 'Fidji',
            'Finlande', 'France', 'Gabon', 'Gambie', 'Géorgie', 'Ghana', 'Grèce', 'Grenade', 'Guatemala', 'Guinée',
            'Guinée-Bissau', 'Guinée équatoriale', 'Guyana', 'Haïti', 'Honduras', 'Hongrie', 'Îles Marshall',
            'Îles Salomon', 'Inde', 'Indonésie', 'Irak', 'Iran', 'Irlande', 'Islande', 'Israël', 'Italie', 'Jamaïque',
            'Japon', 'Jordanie', 'Kazakhstan', 'Kenya', 'Kirghizistan', 'Kiribati', 'Kosovo', 'Koweït', 'Laos',
            'Lesotho', 'Lettonie', 'Liban', 'Liberia', 'Libye', 'Liechtenstein', 'Lituanie', 'Luxembourg',
            'Macédoine du Nord', 'Madagascar', 'Malaisie', 'Malawi', 'Maldives', 'Mali', 'Malte', 'Maroc', 'Maurice',
            'Mauritanie', 'Mexique', 'Micronésie', 'Moldavie', 'Monaco', 'Mongolie', 'Monténégro', 'Mozambique',
            'Namibie', 'Nauru', 'Népal', 'Nicaragua', 'Niger', 'Nigéria', 'Norvège', 'Nouvelle-Zélande', 'Oman',
            'Ouganda', 'Ouzbékistan', 'Pakistan', 'Palaos', 'Panama', 'Papouasie-Nouvelle-Guinée', 'Paraguay',
            'Pays-Bas', 'Pérou', 'Philippines', 'Pologne', 'Portugal', 'Qatar', 'République Dominicaine',
            'République Tchèque', 'Roumanie', 'Royaume-Uni', 'Russie', 'Rwanda', 'Saint-Christophe-et-Niévès',
            'Sainte-Lucie', 'Saint-Vincent-et-les-Grenadines', 'Saint-Marin', 'Salvador', 'Samoa', 'Sao Tomé-et-Principe',
            'Sénégal', 'Serbie', 'Seychelles', 'Sierra Leone', 'Singapour', 'Slovaquie', 'Slovénie', 'Somalie',
            'Soudan', 'Soudan du Sud', 'Sri Lanka', 'Suède', 'Suisse', 'Suriname', 'Syrie', 'Tadjikistan', 'Tanzanie',
            'Tchad', 'Thaïlande', 'Timor Oriental', 'Togo', 'Tonga', 'Trinité-et-Tobago', 'Tunisie', 'Turkménistan',
            'Turquie', 'Tuvalu', 'Ukraine', 'Uruguay', 'Vanuatu', 'Vatican', 'Venezuela', 'Vietnam', 'Yémen', 'Zambie',
            'Zimbabwe'
        ];

        DB::table('pays')->insert(array_map(fn($country) => ['nom' => $country], $countries));
    }
}

<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SousFamilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $sousFamilles = [
            ['nom' => 'électrique', 'famille_id' => 1, 'type_affichage_stock' => 1],
            ['nom' => 'mécanique',   'famille_id' => 1, 'type_affichage_stock' => 1],
            ['nom' => 'Boulonnerie',          'famille_id' => 3, 'type_affichage_stock' => 2],
            ['nom' => 'Robinetterie',         'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Support Robinetterie', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Raccord',    'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'G12',       'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'GC22',      'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Galette',   'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Serrurerie', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Fond Bombés',          'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Accès',     'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Disque plat',          'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Dalot',     'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Coude',     'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Cornière Perf',        'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Cornière',  'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Supportage', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Collet',    'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Collier',   'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'Bride',     'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Carré',     'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Cale de Bois',         'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'BTH',       'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Bouchon',   'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Bossage',   'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Tôle',      'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Cadre Parquet',        'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Rond',      'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Rond Té',   'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Té',        'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Vis',       'famille_id' => 3, 'type_affichage_stock' => 2],
            ['nom' => 'Écrou',     'famille_id' => 3, 'type_affichage_stock' => 2],
            ['nom' => 'Platine',   'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Manchon',   'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Tôle Larmée',          'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Crinoline', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Marche',    'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Tube',      'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'ObturaTé',  'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Purgeur',   'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'AdaptaTé',  'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Chandelier', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Bande Insonorisante',  'famille_id' => 3, 'type_affichage_stock' => 2],
            ['nom' => 'Réduction', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'ECHELLE 8', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'ECHELLE 4', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'ECHELLE 9', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'MANOMETRE', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'TRANSMETTEUR',         'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Niveau',    'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Tableautin', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Traversée', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Sonde',     'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'THERMOSTAT', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'THERMOMETRE',          'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'CONTROLEUR', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'ARMOIRE',   'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'REDUCTION', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'FILTRE',    'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'TE REDUIT', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'UPN',       'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Vanne',     'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Selle PE',  'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Fourreau',  'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Poutrelle', 'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Plat',      'famille_id' => 2, 'type_affichage_stock' => 2],
            ['nom' => 'Piton',     'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'MP1',       'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'MF2',       'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'MF1',       'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'GC12',      'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Galette percée',       'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Rond Teflon',          'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'Obturateur', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Adaptateur', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'TE',        'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Manchette', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Accessoire', 'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'Réchauffeur à Ailette', 'famille_id' => 2, 'type_affichage_stock' => 1],
            ['nom' => 'Joint',     'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'Confort',   'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'Nettoyage', 'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'EPI',       'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'Autre',     'famille_id' => 3, 'type_affichage_stock' => 1],
            ['nom' => 'Certificat',     'famille_id' => 4, 'type_affichage_stock' => 1],

        ];

        DB::beginTransaction();

        foreach ($sousFamilles as $sousFamille) {
            \App\Models\SousFamille::create($sousFamille);
        }
        DB::commit();
    }
}

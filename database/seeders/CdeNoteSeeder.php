<?php

namespace Database\Seeders;

use App\Models\CdeNote;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CdeNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CdeNote::create(
            [
                'contenu' => 'La matière sera acceptée sous réserve de conformité avec notre demande de prix initiale. Notre responsabilité ne pourra être engagée.',
                'entite_id' => 1,
            ]);
            CdeNote::create(
                [
                    'contenu' => 'Tout bon de livraison doit être signé par une personne de chez Atlantis Montaza. Dans le cas contraire, nous ne serions tenus responsables pour tout manquement.',
                        'entite_id' => 1,
                ]);
            CdeNote::create(
                [
                    'contenu' => 'Livraison et conditions de règlement suivant accord cadre.',
                        'entite_id' => 1,
                ]
            );
            CdeNote::create(
                [
                    'contenu' => 'Dans le cas de qualité non avérée, les frais refacturés par le client vous seront imputés.',
                        'entite_id' => 1,
                ]
            );
            CdeNote::create(
                [
                    'contenu' => 'La matière sera acceptée sous réserve de conformité avec notre demande de prix initiale. Notre responsabilité ne pourra être engagée.',
                        'entite_id' => 2,
                ]
            );
            CdeNote::create(
                [
                    'contenu' => 'Tout bon de livraison doit être signé par une personne de chez Atlantis Ventilation. Dans le cas contraire, nous ne serions tenus responsables pour tout manquement.',
                        'entite_id' => 2,
                ]
            );
            CdeNote::create(
                [
                    'contenu' => 'La matière sera acceptée sous réserve de conformité avec notre demande de prix initiale. Notre responsabilité ne pourra être engagée.',
                        'entite_id' => 3,
                ]
            );
            CdeNote::create(
                [
                    'contenu' => 'Tout bon de livraison doit être signé par une personne de chez AMB. Dans le cas contraire, nous ne serions tenus responsables pour tout manquement.',
                    'entite_id' => 3,
                ]
            );

    }
}

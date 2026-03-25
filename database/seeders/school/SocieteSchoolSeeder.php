<?php

namespace Database\Seeders\school;

use App\Models\Commentaire;
use App\Models\Etablissement;
use App\Models\Societe;
use App\Models\SocieteContact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocieteSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $societe = Societe::create([
            'raison_sociale' => "EXAMPLE COMPANY A",
            'siren' => '123456789',
            'forme_juridique_id' => 1,
            'code_ape_id' => 101,
            'societe_type_id' => 1,
            'telephone' => null,
            'email' => null,
            'site_web' => 'www.example-a.com',
            'numero_tva' => 'FR123456789',
            'condition_paiement_id' => 1,
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        $etablissement = Etablissement::create([
            'adresse' => '123 Example Street',
            'nom' => 'EXAMPLE COMPANY A BRANCH',
            'code_postal' => '12345',
            'ville' => 'Example City',
            'region' => 'Example Region',
            'pay_id' => 1,
            'societe_id' => $societe->id,
            'siret' => '12345678900001',
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        SocieteContact::create([
            'nom' => 'John Doe',
            'fonction' => 'Manager',
            'email' => 'johndoe@example.com',
            'telephone_fixe' => '0102030405',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Jane Smith',
            'fonction' => 'Sales',
            'email' => 'janesmith@example.com',
            'telephone_fixe' => '0102030406',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        $societe = Societe::create([
            'raison_sociale' => "EXAMPLE COMPANY B",
            'siren' => '987654321',
            'forme_juridique_id' => 2,
            'code_ape_id' => 202,
            'societe_type_id' => 2,
            'telephone' => '0203040506',
            'email' => null,
            'site_web' => 'www.example-b.com',
            'numero_tva' => 'FR987654321',
            'condition_paiement_id' => 2,
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        $etablissement = Etablissement::create([
            'adresse' => '456 Another Street',
            'nom' => 'EXAMPLE COMPANY B BRANCH',
            'code_postal' => '54321',
            'ville' => 'Another City',
            'region' => 'Another Region',
            'pay_id' => 2,
            'societe_id' => $societe->id,
            'siret' => '98765432100002',
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        SocieteContact::create([
            'nom' => 'Alice Johnson',
            'fonction' => 'Support',
            'email' => 'alicejohnson@example.com',
            'telephone_fixe' => '0203040507',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

    }
}

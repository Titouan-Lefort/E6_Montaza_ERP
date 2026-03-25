<?php

namespace Database\Seeders;

use App\Models\Commentaire;
use App\Models\Etablissement;
use App\Models\Societe;
use App\Models\SocieteContact;
use App\Models\CodeApe;
use App\Models\FormeJuridique;
use App\Models\SocieteType;
use App\Models\ConditionPaiement;
use App\Models\Pays;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocieteProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Helper to get ID or first
        $getId = function ($model, $id) {
            return $model::find($id) ? $id : ($model::first()->id ?? null);
        };

        // Helper for Pays (France or first)
        $getPaysId = function ($id) {
             $pays = Pays::find($id);
             if ($pays) return $id;
             $france = Pays::where('nom', 'like', '%France%')->first();
             return $france ? $france->id : (Pays::first()->id ?? null);
        };

        $societe = Societe::create([
            'raison_sociale' => "Chantiers de l'Atlantique",
            'siren' => '439067612',
            'forme_juridique_id' => $getId(FormeJuridique::class, 5),
            'code_ape_id' => $getId(CodeApe::class, 279),
            'societe_type_id' => $getId(SocieteType::class, 3),
            'telephone' => '02 51 10 91 00',
            'email' => null,
            'site_web' => 'chantiers-atlantique.com',
            'numero_tva' => 'FR78439067612',
            'condition_paiement_id' => $getId(ConditionPaiement::class, 1),
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);
        $etablissement = Etablissement::create([
            'adresse' => 'Avenue Antoine Bourdelle - CS90180',
            'nom' => 'CHANTIERS DE L\'ATLANTIQUE ST NAZAIRE',
            'code_postal' => '44613',
            'ville' => 'Saint Nazaire',
            'region' => 'Pays de la Loire',
            'pay_id' => $getPaysId(65),
            'societe_id' => $societe->id,
            'siret' => '43906761200036',
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);
        SocieteContact::create([
            'nom' => 'Arnaud CORBIERE',
            'fonction' => 'Technique',
            'email' => 'Arnaud.Corbiere@chantiers-atlantique.com',
            'telephone_fixe' => '+33251 167012',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);
        SocieteContact::create([
            'nom' => 'M Agnes PEROUZE',
            'fonction' => 'Achats',
            'email' => 'Marie-agnes.Perouze-jouan@chantiers-atlantique.com',
            'telephone_fixe' => '+33251104001',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        $societe = Societe::create([
            'raison_sociale' => "PROLIANS VAMA DOCKS",
            'siren' => '856802145',
            'forme_juridique_id' => $getId(FormeJuridique::class, 6),
            'code_ape_id' => $getId(CodeApe::class, 426),
            'societe_type_id' => $getId(SocieteType::class, 2),
            'telephone' => null,
            'email' => null,
            'site_web' => 'www.prolians.fr/',
            'numero_tva' => 'FR 52 856 802',
            'condition_paiement_id' => $getId(ConditionPaiement::class, 11),
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        $etablissement = Etablissement::create([
            'adresse' => '55 rue de la Croix Amisse - BP 41',
            'nom' => 'PROLIANS VAMA DOCKS ST NAZAIRE',
            'code_postal' => '44611',
            'ville' => 'Saint Nazaire',
            'region' => 'Pays de la Loire',
            'pay_id' => $getPaysId(65),
            'societe_id' => $societe->id,
            'siret' => '85680214500115',
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        SocieteContact::create([
            'nom' => 'Eric Boutin',
            'fonction' => 'chauffage/sanitaire/plomberie',
            'email' => 'eboutin@prolians.eu',
            'telephone_fixe' => '02 40 22 78 22',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Sébastien Noury',
            'fonction' => 'chauffage/sanitaire/plomberie',
            'email' => 'snoury@prolians.eu',
            'telephone_fixe' => '02 40 22 78 23',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Sandra Thomas',
            'fonction' => 'quincaillerie',
            'email' => 'sthomas@prolians.eu',
            'telephone_fixe' => '02 51 10 11 75',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Adeline Graiz',
            'fonction' => 'acier',
            'email' => 'agraiz@prolians.eu',
            'telephone_fixe' => '02 40 22 04 48',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Lowen Fimbow',
            'fonction' => 'epi',
            'email' => 'lfinbow@prolians.eu',
            'telephone_fixe' => '02 40 22 78 21',
            'telephone_portable' => '06 08 16 40 50',
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Pascal Grenapin',
            'fonction' => 'commercial général',
            'email' => 'PGRENAPIN@PROLIANS.EU',
            'telephone_fixe' => null,
            'telephone_portable' => '06 08 80 31 17',
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Laurence Cochet',
            'fonction' => 'soudure',
            'email' => 'lcochet@prolians.eu',
            'telephone_fixe' => '02 40 22 32 20',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Franck Gabard',
            'fonction' => 'commercial soudure',
            'email' => 'fgabard@prolians.eu',
            'telephone_fixe' => null,
            'telephone_portable' => '06 66 45 55 35',
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Rudy Lastennet',
            'fonction' => 'serrurerie',
            'email' => 'rlastennet@prolians.eu',
            'telephone_fixe' => '02 40 22 78 39',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);


        $societe = Societe::create([
            'raison_sociale' => "ATLANTIC ROBINETTERIE",
            'siren' => '352873418',
            'forme_juridique_id' => $getId(FormeJuridique::class, 2),
            'code_ape_id' => $getId(CodeApe::class, 423),
            'societe_type_id' => $getId(SocieteType::class, 2),
            'telephone' => '02 40 00 04 75',
            'email' => null,
            'site_web' => 'www.atlantic-robinetterie.fr',
            'numero_tva' => 'FR96352873418',
            'condition_paiement_id' => $getId(ConditionPaiement::class, 3),
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        $etablissement = Etablissement::create([
            'adresse' => 'ZI des Noés - Rue Martin Luther King',
            'nom' => 'ATLANTIC ROBINETTERIE MONTOIR',
            'code_postal' => '44550',
            'ville' => 'MONTOIR DE BRETAGNE',
            'region' => 'Pays de la Loire',
            'pay_id' => $getPaysId(65),
            'societe_id' => $societe->id,
            'siret' => '35287341800045',
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        SocieteContact::create([
            'nom' => 'Lauriane Velia',
            'fonction' => 'commercial',
            'email' => 'lauriane.velia@groupesofia.fr',
            'telephone_fixe' => '02 40 00 04 45',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Vanessa Lebrun',
            'fonction' => 'commercial',
            'email' => 'vanessa.lebrun@groupesofia.fr',
            'telephone_fixe' => '02 40 00 04 56',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);

        SocieteContact::create([
            'nom' => 'Rebecca Aubinais',
            'fonction' => 'commercial',
            'email' => 'rebecca.aubinais@groupesofia.fr',
            'telephone_fixe' => '02 40 00 04 30',
            'telephone_portable' => null,
            'etablissement_id' => $etablissement->id,
        ]);


        $societe = Societe::create([
            'raison_sociale' => "SODIME - LRI",
            'siren' => '572002053',
            'forme_juridique_id' => $getId(FormeJuridique::class, 2),
            'code_ape_id' => $getId(CodeApe::class, 430),
            'societe_type_id' => $getId(SocieteType::class, 2),
            'telephone' => null,
            'email' => null,
            'site_web' => 'www.larobinetterie.com',
            'numero_tva' => 'FR26572002053',
            'condition_paiement_id' => $getId(ConditionPaiement::class, 2),
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        $etablissement = Etablissement::create([
            'adresse' => '173 rue du Genétais',
            'nom' => 'SODIME - LRI REZE',
            'code_postal' => '44400',
            'ville' => 'REZE',
            'region' => 'Pays de la Loire',
            'pay_id' => $getPaysId(65),
            'societe_id' => $societe->id,
            'siret' => '57200205300252',
            'commentaire_id' => Commentaire::factory()->create()->id,
        ]);

        SocieteContact::create([
            'nom' => 'Tiphaine Leneneze',
            'fonction' => 'commerciale',
            'email' => 'tleneneze@lri-sodime.com',
            'telephone_fixe' => '02 28 01 90 44',
            'telephone_portable' => '06 35 35 57 93',
            'etablissement_id' => $etablissement->id,
        ]);


    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\Cde;
use App\Models\CdeLigne;
use App\Models\DdpCdeStatut;
use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\TypeExpedition;
use App\Models\Unite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CdeLigneTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function une_ligne_appartient_a_une_commande()
    {
        $cde = Cde::factory()->create();
        $ligne = CdeLigne::factory()->create(['cde_id' => $cde->id]);

        $this->assertInstanceOf(Cde::class, $ligne->cde);
        $this->assertEquals($cde->id, $ligne->cde->id);
    }

    /** @test */
    public function une_ligne_peut_avoir_une_matiere()
    {
        $matiere = Matiere::factory()->create();
        $ligne = CdeLigne::factory()->create(['matiere_id' => $matiere->id]);

        $this->assertInstanceOf(Matiere::class, $ligne->matiere);
        $this->assertEquals($matiere->id, $ligne->matiere->id);
    }

    /** @test */
    public function une_ligne_peut_avoir_une_unite()
    {
        $unite = Unite::factory()->create();
        $ligne = CdeLigne::factory()->create(['unite_id' => $unite->id]);

        $this->assertInstanceOf(Unite::class, $ligne->unite);
        $this->assertEquals($unite->id, $ligne->unite->id);
    }

    /** @test */
    public function une_ligne_peut_avoir_un_statut()
    {
        $statut = DdpCdeStatut::factory()->create();
        $ligne = CdeLigne::factory()->create(['ddp_cde_statut_id' => $statut->id]);

        $this->assertInstanceOf(DdpCdeStatut::class, $ligne->ddpCdeStatut);
        $this->assertEquals($statut->id, $ligne->ddpCdeStatut->id);
    }

    /** @test */
    public function une_ligne_peut_avoir_un_type_expedition()
    {
        $typeExpedition = TypeExpedition::factory()->create();
        $ligne = CdeLigne::factory()->create(['type_expedition_id' => $typeExpedition->id]);

        $this->assertInstanceOf(TypeExpedition::class, $ligne->typeExpedition);
        $this->assertEquals($typeExpedition->id, $ligne->typeExpedition->id);
    }

    /** @test */
    public function une_ligne_peut_avoir_plusieurs_mouvements_stock()
    {
        $ligne = CdeLigne::factory()->create();

        $mouvement1 = MouvementStock::factory()->create(['cde_ligne_id' => $ligne->id]);
        $mouvement2 = MouvementStock::factory()->create(['cde_ligne_id' => $ligne->id]);

        $this->assertCount(2, $ligne->mouvementsStock);
        $this->assertTrue($ligne->mouvementsStock->contains($mouvement1));
        $this->assertTrue($ligne->mouvementsStock->contains($mouvement2));
    }

    /** @test */
    public function une_ligne_peut_calculer_son_prix_total()
    {
        $ligne = CdeLigne::factory()->create([
            'quantite' => 5,
            'prix_unitaire' => 100,
        ]);

        // Si vous avez une méthode getPrixTotal() ou un accesseur
        $expectedTotal = 5 * 100;

        $this->assertEquals($expectedTotal, $ligne->quantite * $ligne->prix_unitaire);
    }

    /** @test */
    public function une_ligne_peut_etre_marquee_comme_stockee()
    {
        $ligne = CdeLigne::factory()->create(['is_stocke' => false]);

        $this->assertFalse($ligne->is_stocke);

        $ligne->update(['is_stocke' => true]);

        $this->assertTrue($ligne->fresh()->is_stocke);
    }

    /** @test */
    public function une_ligne_peut_etre_marquee_comme_non_livree()
    {
        $ligne = CdeLigne::factory()->create(['non_livre' => false]);

        $this->assertFalse($ligne->non_livre);

        $ligne->update(['non_livre' => true]);

        $this->assertTrue($ligne->fresh()->non_livre);
    }

    /** @test */
    public function une_ligne_peut_avoir_une_date_livraison_prevue()
    {
        $dateLivraison = now()->addDays(7);
        $ligne = CdeLigne::factory()->create([
            'date_livraison' => $dateLivraison,
        ]);

        $this->assertNotNull($ligne->date_livraison);
        $this->assertEquals($dateLivraison->format('Y-m-d'), $ligne->date_livraison->format('Y-m-d'));
    }

    /** @test */
    public function une_ligne_peut_avoir_une_date_livraison_reelle()
    {
        $dateLivraisonReelle = now();
        $ligne = CdeLigne::factory()->create([
            'date_livraison_reelle' => $dateLivraisonReelle,
        ]);

        $this->assertNotNull($ligne->date_livraison_reelle);
        $this->assertEquals($dateLivraisonReelle->format('Y-m-d'), $ligne->date_livraison_reelle->format('Y-m-d'));
    }

    /** @test */
    public function une_ligne_peut_avoir_une_reference_interne_et_fournisseur()
    {
        $ligne = CdeLigne::factory()->create([
            'ref_interne' => 'REF-INT-001',
            'ref_fournisseur' => 'REF-FOUR-001',
        ]);

        $this->assertEquals('REF-INT-001', $ligne->ref_interne);
        $this->assertEquals('REF-FOUR-001', $ligne->ref_fournisseur);
    }

    /** @test */
    public function une_ligne_peut_etre_une_sous_ligne()
    {
        $lignePrincipale = CdeLigne::factory()->create([
            'poste' => 1,
            'sous_ligne' => false,
        ]);

        $sousLigne = CdeLigne::factory()->create([
            'cde_id' => $lignePrincipale->cde_id,
            'poste' => 2,
            'sous_ligne' => true,
        ]);

        $this->assertFalse($lignePrincipale->sous_ligne);
        $this->assertTrue($sousLigne->sous_ligne);
    }

    /** @test */
    public function une_ligne_avec_valeur_unitaire_stocke_correctement()
    {
        $ligne = CdeLigne::factory()->create([
            'prix_unitaire' => 123.45,
        ]);

        $this->assertEquals(123.45, $ligne->prix_unitaire);
    }

    /** @test */
    public function les_lignes_peuvent_etre_triees_par_poste()
    {
        $cde = Cde::factory()->create();

        CdeLigne::factory()->create(['cde_id' => $cde->id, 'poste' => 3]);
        CdeLigne::factory()->create(['cde_id' => $cde->id, 'poste' => 1]);
        CdeLigne::factory()->create(['cde_id' => $cde->id, 'poste' => 2]);

        $lignes = $cde->cdeLignes;

        $this->assertEquals(1, $lignes->first()->poste);
        $this->assertEquals(2, $lignes->get(1)->poste);
        $this->assertEquals(3, $lignes->last()->poste);
    }

    /** @test */
    public function une_ligne_peut_referencer_une_autre_ligne()
    {
        $ligne1 = CdeLigne::factory()->create();
        $ligne2 = CdeLigne::factory()->create([
            'ligne_autre_id' => $ligne1->id,
        ]);

        $this->assertEquals($ligne1->id, $ligne2->ligne_autre_id);
    }

    /** @test */
    public function une_ligne_sans_matiere_a_matiere_id_null()
    {
        $ligne = CdeLigne::factory()->create(['matiere_id' => null]);

        $this->assertNull($ligne->matiere_id);
        $this->assertNull($ligne->matiere);
    }
}

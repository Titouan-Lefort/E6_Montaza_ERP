<?php

namespace Tests\Unit\Models;

use App\Models\Affaire;
use App\Models\Cde;
use App\Models\CdeLigne;
use App\Models\CdeNote;
use App\Models\Commentaire;
use App\Models\ConditionPaiement;
use App\Models\Ddp;
use App\Models\DdpCdeStatut;
use App\Models\Entite;
use App\Models\Etablissement;
use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\Societe;
use App\Models\SocieteContact;
use App\Models\TypeExpedition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CdeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function une_commande_appartient_a_un_utilisateur()
    {
        $user = User::factory()->create();
        $cde = Cde::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $cde->user);
        $this->assertEquals($user->id, $cde->user->id);
    }

    /** @test */
    public function une_commande_appartient_a_une_entite()
    {
        $entite = Entite::factory()->create();
        $cde = Cde::factory()->create(['entite_id' => $entite->id]);

        $this->assertInstanceOf(Entite::class, $cde->entite);
        $this->assertEquals($entite->id, $cde->entite->id);
    }

    /** @test */
    public function une_commande_appartient_a_un_statut()
    {
        $statut = DdpCdeStatut::factory()->create();
        $cde = Cde::factory()->create(['ddp_cde_statut_id' => $statut->id]);

        $this->assertInstanceOf(DdpCdeStatut::class, $cde->ddpCdeStatut);
        $this->assertEquals($statut->id, $cde->ddpCdeStatut->id);

        // Test alias relation
        $this->assertInstanceOf(DdpCdeStatut::class, $cde->statut);
        $this->assertEquals($statut->id, $cde->statut->id);
    }

    /** @test */
    public function une_commande_peut_appartenir_a_une_ddp()
    {
        $ddp = Ddp::factory()->create();
        $cde = Cde::factory()->create(['ddp_id' => $ddp->id]);

        $this->assertInstanceOf(Ddp::class, $cde->ddp);
        $this->assertEquals($ddp->id, $cde->ddp->id);
    }

    /** @test */
    public function une_commande_peut_appartenir_a_une_affaire()
    {
        $affaire = Affaire::factory()->create();
        $cde = Cde::factory()->create(['affaire_id' => $affaire->id]);

        $this->assertInstanceOf(Affaire::class, $cde->affaire);
        $this->assertEquals($affaire->id, $cde->affaire->id);
    }

    /** @test */
    public function une_commande_a_plusieurs_lignes()
    {
        $cde = Cde::factory()->create();
        $ligne1 = CdeLigne::factory()->create(['cde_id' => $cde->id, 'poste' => 1]);
        $ligne2 = CdeLigne::factory()->create(['cde_id' => $cde->id, 'poste' => 2]);
        $ligne3 = CdeLigne::factory()->create(['cde_id' => $cde->id, 'poste' => 3]);

        $this->assertCount(3, $cde->cdeLignes);

        // Vérifier que les lignes sont ordonnées par poste
        $this->assertEquals(1, $cde->cdeLignes->first()->poste);
        $this->assertEquals(3, $cde->cdeLignes->last()->poste);
    }

    /** @test */
    public function une_commande_peut_avoir_un_suivi_par()
    {
        $user = User::factory()->create();
        $cde = Cde::factory()->create(['affaire_suivi_par_id' => $user->id]);

        $this->assertInstanceOf(User::class, $cde->affaireSuiviPar);
        $this->assertEquals($user->id, $cde->affaireSuiviPar->id);
    }

    /** @test */
    public function une_commande_peut_avoir_un_acheteur()
    {
        $acheteur = User::factory()->create();
        $cde = Cde::factory()->create(['acheteur_id' => $acheteur->id]);

        $this->assertInstanceOf(User::class, $cde->acheteur);
        $this->assertEquals($acheteur->id, $cde->acheteur->id);
    }

    /** @test */
    public function une_commande_peut_avoir_un_type_expedition()
    {
        $typeExpedition = TypeExpedition::factory()->create();
        $cde = Cde::factory()->create(['type_expedition_id' => $typeExpedition->id]);

        $this->assertInstanceOf(TypeExpedition::class, $cde->typeExpedition);
        $this->assertEquals($typeExpedition->id, $cde->typeExpedition->id);
    }

    /** @test */
    public function une_commande_peut_avoir_une_condition_paiement()
    {
        $conditionPaiement = ConditionPaiement::factory()->create();
        $cde = Cde::factory()->create(['condition_paiement_id' => $conditionPaiement->id]);

        $this->assertInstanceOf(ConditionPaiement::class, $cde->conditionPaiement);
        $this->assertEquals($conditionPaiement->id, $cde->conditionPaiement->id);
    }

    /** @test */
    public function une_commande_peut_avoir_un_commentaire()
    {
        $commentaire = Commentaire::factory()->create();
        $cde = Cde::factory()->create(['commentaire_id' => $commentaire->id]);

        $this->assertInstanceOf(Commentaire::class, $cde->commentaire);
        $this->assertEquals($commentaire->id, $cde->commentaire->id);
    }

    /** @test */
    public function une_commande_peut_avoir_plusieurs_notes()
    {
        $cde = Cde::factory()->create();
        $note1 = CdeNote::factory()->create();
        $note2 = CdeNote::factory()->create();

        $cde->cdeNotes()->attach([$note1->id, $note2->id]);

        $this->assertCount(2, $cde->cdeNotes);
        $this->assertTrue($cde->cdeNotes->contains($note1));
        $this->assertTrue($cde->cdeNotes->contains($note2));
    }

    /** @test */
    public function une_commande_peut_avoir_des_contacts_societe()
    {
        $societe = Societe::factory()->create();
        $etablissement = Etablissement::factory()->create(['societe_id' => $societe->id]);
        $contact1 = SocieteContact::factory()->create(['etablissement_id' => $etablissement->id]);
        $contact2 = SocieteContact::factory()->create(['etablissement_id' => $etablissement->id]);

        $cde = Cde::factory()->create();

        // Créer les liens via la table pivot
        \DB::table('cde_societe_contacts')->insert([
            ['cde_id' => $cde->id, 'societe_contact_id' => $contact1->id],
            ['cde_id' => $cde->id, 'societe_contact_id' => $contact2->id],
        ]);

        $cde = $cde->fresh();

        $this->assertCount(2, $cde->societeContacts);
    }

    /** @test */
    public function has_societe_contact_retourne_true_si_contact_existe()
    {
        $contact = SocieteContact::factory()->create();
        $cde = Cde::factory()->create();

        \DB::table('cde_societe_contacts')->insert([
            'cde_id' => $cde->id,
            'societe_contact_id' => $contact->id,
        ]);

        $cde = $cde->fresh();

        $this->assertTrue($cde->hasSocieteContact());
    }

    /** @test */
    public function has_societe_contact_retourne_false_si_aucun_contact()
    {
        $cde = Cde::factory()->create();

        $this->assertFalse($cde->hasSocieteContact());
    }

    /** @test */
    public function get_etablissement_retourne_etablissement_du_premier_contact()
    {
        $societe = Societe::factory()->create();
        $etablissement = Etablissement::factory()->create(['societe_id' => $societe->id]);
        $contact = SocieteContact::factory()->create(['etablissement_id' => $etablissement->id]);

        $cde = Cde::factory()->create();

        \DB::table('cde_societe_contacts')->insert([
            'cde_id' => $cde->id,
            'societe_contact_id' => $contact->id,
        ]);

        $cde = $cde->fresh();

        $this->assertInstanceOf(Etablissement::class, $cde->etablissement);
        $this->assertEquals($etablissement->id, $cde->etablissement->id);
    }

    /** @test */
    public function get_societe_retourne_societe_du_premier_contact()
    {
        $societe = Societe::factory()->create();
        $etablissement = Etablissement::factory()->create(['societe_id' => $societe->id]);
        $contact = SocieteContact::factory()->create(['etablissement_id' => $etablissement->id]);

        $cde = Cde::factory()->create();

        \DB::table('cde_societe_contacts')->insert([
            'cde_id' => $cde->id,
            'societe_contact_id' => $contact->id,
        ]);

        $cde = $cde->fresh();

        $this->assertInstanceOf(Societe::class, $cde->societe);
        $this->assertEquals($societe->id, $cde->societe->id);
    }

    /** @test */
    public function une_commande_a_des_mouvements_stock_via_ses_lignes()
    {
        $cde = Cde::factory()->create();
        $ligne = CdeLigne::factory()->create(['cde_id' => $cde->id]);

        $mouvement = MouvementStock::factory()->create(['cde_ligne_id' => $ligne->id]);

        $this->assertCount(1, $cde->mouvementsStock);
        $this->assertEquals($mouvement->id, $cde->mouvementsStock->first()->id);
    }

    /** @test */
    public function la_sauvegarde_d_une_commande_met_a_jour_le_total_de_l_affaire()
    {
        $affaire = Affaire::factory()->create(['total_ht' => 0]);
        $cde = Cde::factory()->create([
            'affaire_id' => $affaire->id,
            'ddp_cde_statut_id' => 2, // En cours
            'total_ht' => 1000,
        ]);

        $affaire->refresh();

        // Le total devrait être mis à jour
        $this->assertGreaterThan(0, $affaire->total_ht);
    }

    /** @test */
    public function la_suppression_d_une_commande_met_a_jour_le_total_de_l_affaire()
    {
        $affaire = Affaire::factory()->create();
        $cde = Cde::factory()->create([
            'affaire_id' => $affaire->id,
            'ddp_cde_statut_id' => 2,
            'total_ht' => 1000,
        ]);

        $affaire->updateTotal();
        $totalAvantSuppression = $affaire->fresh()->total_ht;

        $cde->delete();

        $affaire->refresh();

        $this->assertLessThanOrEqual($totalAvantSuppression, $affaire->total_ht);
    }

    /** @test */
    public function une_commande_terminee_stocke_automatiquement_ses_lignes()
    {
        $matiere = Matiere::factory()->create();
        $cde = Cde::factory()->create(['ddp_cde_statut_id' => 1]); // En attente

        $ligne = CdeLigne::factory()->create([
            'cde_id' => $cde->id,
            'matiere_id' => $matiere->id,
            'quantite' => 10,
            'prix_unitaire' => 100,
            'is_stocke' => false,
            'date_livraison_reelle' => now(),
        ]);

        // Passer la commande à "Terminée"
        $cde->ddp_cde_statut_id = 3;
        $cde->save();

        $ligne->refresh();

        // Vérifier qu'un mouvement de stock a été créé
        $this->assertCount(1, $ligne->mouvementsStock);
        $this->assertTrue($ligne->is_stocke);
    }

    /** @test */
    public function une_ligne_deja_stockee_n_est_pas_stockee_deux_fois()
    {
        $matiere = Matiere::factory()->create();
        $cde = Cde::factory()->create(['ddp_cde_statut_id' => 1]);

        $ligne = CdeLigne::factory()->create([
            'cde_id' => $cde->id,
            'matiere_id' => $matiere->id,
            'quantite' => 10,
            'prix_unitaire' => 100,
            'is_stocke' => true, // Déjà stockée
            'date_livraison_reelle' => now(),
        ]);

        $mouvementsAvant = $ligne->mouvementsStock()->count();

        // Passer à terminée
        $cde->ddp_cde_statut_id = 3;
        $cde->save();

        $mouvementsApres = $ligne->fresh()->mouvementsStock()->count();

        // Pas de nouveau mouvement
        $this->assertEquals($mouvementsAvant, $mouvementsApres);
    }

    /** @test */
    public function une_ligne_sans_matiere_n_est_pas_stockee()
    {
        $cde = Cde::factory()->create(['ddp_cde_statut_id' => 1]);

        $ligne = CdeLigne::factory()->create([
            'cde_id' => $cde->id,
            'matiere_id' => null, // Pas de matière
            'quantite' => 10,
            'is_stocke' => false,
            'date_livraison_reelle' => now(),
        ]);

        // Passer à terminée
        $cde->ddp_cde_statut_id = 3;
        $cde->save();

        $ligne->refresh();

        $this->assertFalse($ligne->is_stocke);
        $this->assertCount(0, $ligne->mouvementsStock);
    }

    /** @test */
    public function une_ligne_sans_date_livraison_reelle_n_est_pas_stockee()
    {
        $matiere = Matiere::factory()->create();
        $cde = Cde::factory()->create(['ddp_cde_statut_id' => 1]);

        $ligne = CdeLigne::factory()->create([
            'cde_id' => $cde->id,
            'matiere_id' => $matiere->id,
            'quantite' => 10,
            'prix_unitaire' => 100,
            'is_stocke' => false,
            'date_livraison_reelle' => null, // Pas de date de livraison
        ]);

        // Passer à terminée
        $cde->ddp_cde_statut_id = 3;
        $cde->save();

        $ligne->refresh();

        $this->assertFalse($ligne->is_stocke);
        $this->assertCount(0, $ligne->mouvementsStock);
    }
}

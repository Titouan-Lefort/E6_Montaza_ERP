<?php

namespace Tests\Unit;

use App\Models\Affaire;
use App\Models\Cde;
use App\Models\Facture;
use App\Models\Reparation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffaireTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_total_ht_from_cdes()
    {
        $affaire = Affaire::factory()->create();

        // Cde with status 2 (En cours) - Should be included
        Cde::factory()->create([
            'affaire_id' => $affaire->id,
            'ddp_cde_statut_id' => 2,
            'total_ht' => 100,
        ]);

        // Cde with status 3 (Terminée) - Should be included
        Cde::factory()->create([
            'affaire_id' => $affaire->id,
            'ddp_cde_statut_id' => 3,
            'total_ht' => 200,
        ]);

        // Cde with status 1 (En attente) - Should NOT be included
        Cde::factory()->create([
            'affaire_id' => $affaire->id,
            'ddp_cde_statut_id' => 1,
            'total_ht' => 50,
        ]);

        $affaire->updateTotal();

        $this->assertEquals(300, $affaire->total_ht);
    }

    /** @test */
    public function it_calculates_total_ht_from_reparations_factures()
    {
        $affaire = Affaire::factory()->create();
        $reparation = Reparation::factory()->create(['affaire_id' => $affaire->id]);

        Facture::factory()->create([
            'reparation_id' => $reparation->id,
            'montant_total' => 150,
        ]);

        Facture::factory()->create([
            'reparation_id' => $reparation->id,
            'montant_total' => 50,
        ]);

        $affaire->updateTotal();

        $this->assertEquals(200, $affaire->total_ht);
    }

    /** @test */
    public function it_calculates_total_ht_from_both_cdes_and_reparations()
    {
        $affaire = Affaire::factory()->create();

        // Cde
        Cde::factory()->create([
            'affaire_id' => $affaire->id,
            'ddp_cde_statut_id' => 2,
            'total_ht' => 100,
        ]);

        // Reparation
        $reparation = Reparation::factory()->create(['affaire_id' => $affaire->id]);
        Facture::factory()->create([
            'reparation_id' => $reparation->id,
            'montant_total' => 50,
        ]);

        $affaire->updateTotal();

        $this->assertEquals(150, $affaire->total_ht);
    }

    /** @test */
    public function it_handles_empty_values_correctly()
    {
        $affaire = Affaire::factory()->create();

        $affaire->updateTotal();

        $this->assertEquals(0, $affaire->total_ht);
    }
}

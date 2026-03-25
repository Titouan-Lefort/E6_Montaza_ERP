<?php

namespace Tests\Feature;

use App\Models\Affaire;
use App\Models\Facture;
use App\Models\Reparation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReparationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_facture_updates_affaire_total()
    {
        $affaire = Affaire::factory()->create(['total_ht' => 0]);
        $reparation = Reparation::factory()->create(['affaire_id' => $affaire->id]);

        $facture = Facture::factory()->create([
            'reparation_id' => $reparation->id,
            'montant_total' => 100,
        ]);

        $this->assertEquals(100, $affaire->fresh()->total_ht);
    }

    /** @test */
    public function updating_a_facture_updates_affaire_total()
    {
        $affaire = Affaire::factory()->create(['total_ht' => 0]);
        $reparation = Reparation::factory()->create(['affaire_id' => $affaire->id]);

        $facture = Facture::factory()->create([
            'reparation_id' => $reparation->id,
            'montant_total' => 100,
        ]);

        $this->assertEquals(100, $affaire->fresh()->total_ht);

        $facture->update(['montant_total' => 200]);

        $this->assertEquals(200, $affaire->fresh()->total_ht);
    }

    /** @test */
    public function deleting_a_facture_updates_affaire_total()
    {
        $affaire = Affaire::factory()->create(['total_ht' => 0]);
        $reparation = Reparation::factory()->create(['affaire_id' => $affaire->id]);

        $facture = Facture::factory()->create([
            'reparation_id' => $reparation->id,
            'montant_total' => 100,
        ]);

        $this->assertEquals(100, $affaire->fresh()->total_ht);

        $facture->delete();

        $this->assertEquals(0, $affaire->fresh()->total_ht);
    }
}

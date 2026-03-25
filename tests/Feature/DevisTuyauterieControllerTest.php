<?php

namespace Tests\Feature;

use App\Models\DevisTuyauterie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DevisTuyauterieControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_utilisateur_connecte_peut_voir_la_liste_des_devis()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        DevisTuyauterie::factory()->count(3)->create(['is_archived' => false]);

        $response = $this->get(route('devis_tuyauterie.index'));

        $response->assertStatus(200);
        $response->assertViewIs('devis_tuyauterie.index');
        $response->assertViewHas('devis');
    }

    /** @test */
    public function la_liste_des_devis_n_affiche_pas_les_archives()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $active = DevisTuyauterie::factory()->create(['is_archived' => false]);
        $archived = DevisTuyauterie::factory()->create(['is_archived' => true]);

        $response = $this->get(route('devis_tuyauterie.index'));

        $response->assertSee($active->reference_projet);
        $response->assertDontSee($archived->reference_projet);
    }

    /** @test */
    public function un_utilisateur_peut_voir_les_archives()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $active = DevisTuyauterie::factory()->create(['is_archived' => false]);
        $archived = DevisTuyauterie::factory()->create(['is_archived' => true]);

        $response = $this->get(route('devis_tuyauterie.archives'));

        $response->assertStatus(200);
        $response->assertSee($archived->reference_projet);
        $response->assertDontSee($active->reference_projet);
    }

    /** @test */
    public function on_peut_archiver_un_devis()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $devis = DevisTuyauterie::factory()->create(['is_archived' => false]);

        $response = $this->post(route('devis_tuyauterie.archive', $devis->id));

        $response->assertRedirect();
        $this->assertTrue($devis->fresh()->is_archived);
    }

    /** @test */
    public function on_peut_restaurer_un_devis()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $devis = DevisTuyauterie::factory()->create(['is_archived' => true]);

        $response = $this->post(route('devis_tuyauterie.unarchive', $devis->id));

        $response->assertRedirect();
        $this->assertFalse($devis->fresh()->is_archived);
    }

    /** @test */
    public function on_peut_acceder_a_la_page_de_creation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('devis_tuyauterie.create'));
        $response->assertStatus(200);
        $response->assertSeeLivewire('devis-tuyauterie-form');
    }

     /** @test */
    public function on_peut_acceder_a_la_page_d_edition()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $devis = DevisTuyauterie::factory()->create();

        $response = $this->get(route('devis_tuyauterie.edit', $devis->id));
        $response->assertStatus(200);
        $response->assertSeeLivewire('devis-tuyauterie-form');
    }
}

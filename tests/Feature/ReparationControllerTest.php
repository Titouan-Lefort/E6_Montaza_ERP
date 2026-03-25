<?php

namespace Tests\Feature;

use App\Models\Materiel;
use App\Models\Reparation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReparationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_displays_reparations()
    {
        $user = User::factory()->create();
        $reparation = Reparation::factory()->create();

        $response = $this->actingAs($user)->get(route('reparation.index'));

        $response->assertStatus(200);
        $response->assertViewHas('activeReparations');
    }

    /** @test */
    public function store_creates_reparation_and_facture()
    {
        $user = User::factory()->create();
        $materiel = Materiel::factory()->create(['status' => 'actif']);

        $response = $this->actingAs($user)->post(route('reparation.store'), [
            'materiel_id' => $materiel->id,
            'description' => 'Test description',
        ]);

        $response->assertRedirect(route('reparation.index'));

        $this->assertDatabaseHas('reparations', [
            'materiel_id' => $materiel->id,
            'description' => 'Test description',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('factures', [
            'reparation_id' => Reparation::first()->id,
            'montant_total' => 0,
        ]);

        $this->assertEquals('inactif', $materiel->fresh()->status);
    }

    /** @test */
    public function update_updates_reparation_status()
    {
        $user = User::factory()->create();
        $reparation = Reparation::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->actingAs($user)->put(route('reparation.update', $reparation), [
            'description' => 'Updated description',
            'status' => 'in_progress',
        ]);

        $response->assertRedirect(route('reparation.index'));

        $this->assertDatabaseHas('reparations', [
            'id' => $reparation->id,
            'status' => 'in_progress',
            'description' => 'Updated description',
        ]);
    }

    /** @test */
    public function user_with_permission_can_update_other_users_reparation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $reparation = Reparation::factory()->create(['user_id' => $otherUser->id, 'status' => 'pending']);

        // Setup permission
        $role = \App\Models\Role::factory()->create();
        $permission = \App\Models\Permission::factory()->create(['name' => 'gerer_les_reparations']);
        $role->permissions()->attach($permission);
        $user->role_id = $role->id;
        $user->save();

        $response = $this->actingAs($user)->put(route('reparation.update', $reparation), [
            'description' => 'Admin update',
            'status' => 'closed',
        ]);

        $response->assertRedirect(route('reparation.index'));

        $this->assertDatabaseHas('reparations', [
            'id' => $reparation->id,
            'status' => 'closed',
            'description' => 'Admin update',
        ]);
    }
}

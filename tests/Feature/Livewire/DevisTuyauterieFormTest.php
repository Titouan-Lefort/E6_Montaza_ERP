<?php

namespace Tests\Feature\Livewire;

use App\Livewire\DevisTuyauterieForm;
use App\Models\DevisTuyauterie;
use App\Models\Societe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DevisTuyauterieFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function le_composant_se_charge_correctement()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(DevisTuyauterieForm::class)
            ->assertStatus(200)
            ->assertSet('date_emission', now()->format('Y-m-d'));
    }

    /** @test */
    public function on_peut_creer_un_devis()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(DevisTuyauterieForm::class)
            // Remplissage En-tête
            ->set('reference_projet', 'PROJ-TEST-01')
            ->set('client_nom', 'Client Test')
            ->set('lieu_intervention', 'Atelier')
            ->set('date_emission', '2025-01-01')

            // Les sections sont initialisées par défaut avec une ligne
            // On va remplir la première ligne
            ->set('sections.0.titre', 'Lot 1')
            ->set('sections.0.lignes.0.designation', 'Tube Inox')
            ->set('sections.0.lignes.0.quantite', 10)
            ->set('sections.0.lignes.0.prix_unitaire', 100)

            ->call('calculateTotals') // Trigger calcul

            ->assertSet('total_ht', 1000) // 10 * 100

            ->call('save')
            ->assertRedirect(route('devis_tuyauterie.index'));

        $this->assertDatabaseHas('devis_tuyauteries', [
            'reference_projet' => 'PROJ-TEST-01',
            'client_nom' => 'Client Test',
            'total_ht' => 1000,
        ]);
    }

    /** @test */
    public function on_peut_ajouter_et_supprimer_des_sections()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(DevisTuyauterieForm::class)
            ->call('addSection')
            ->assertCount('sections', 2)
            ->call('removeSection', 1)
            ->assertCount('sections', 1);
    }

    /** @test */
    public function calcul_des_totaux_est_correct()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(DevisTuyauterieForm::class)
            ->set('sections', [
                [
                    'titre' => 'S1',
                    'lignes' => [
                        ['type' => 'fourniture', 'designation' => 'A', 'quantite' => 2, 'prix_unitaire' => 10, 'prix_achat' => 5, 'total_ht' => 0, 'matiere' => '', 'unite' => 'u']
                    ]
                ]
            ])
            ->call('calculateTotals')
            ->assertSet('total_ht', 20)
            ->assertSet('marge_globale', 10); // (2*10) - (2*5) = 10
    }

    /** @test */
    public function on_peut_editer_un_devis_existant()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $devis = DevisTuyauterie::factory()->create([
            'reference_projet' => 'OLD-REF',
            'total_ht' => 500
        ]);

        Livewire::test(DevisTuyauterieForm::class, ['devis' => $devis])
            ->assertSet('reference_projet', 'OLD-REF')
            ->set('reference_projet', 'NEW-REF')
            ->call('save')
            ->assertRedirect(route('devis_tuyauterie.index'));

        $this->assertDatabaseHas('devis_tuyauteries', [
            'id' => $devis->id,
            'reference_projet' => 'NEW-REF',
        ]);
    }

    /** @test */
    public function selectionner_une_societe_remplit_les_infos_client()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $societe = Societe::factory()->create(['raison_sociale' => 'Societe SA']);

        Livewire::test(DevisTuyauterieForm::class)
            ->set('societe_id', $societe->id)
            ->assertSet('client_nom', 'Societe SA');
    }
}

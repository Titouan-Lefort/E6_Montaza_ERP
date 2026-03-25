<?php

namespace Tests\Feature;

use App\Models\Personnel;
use App\Models\PersonnelConge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonnelControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur authentifié pour tous les tests
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function un_utilisateur_connecte_peut_voir_la_liste_des_personnels()
    {
        Personnel::factory()->count(3)->create();

        $response = $this->get(route('personnel.index'));

        $response->assertStatus(200);
        $response->assertViewIs('personnel.index');
        $response->assertViewHas('personnels');
    }

    /** @test */
    public function un_utilisateur_peut_rechercher_un_personnel_par_nom()
    {
        $personnel1 = Personnel::factory()->create(['nom' => 'Dupont', 'prenom' => 'Jean']);
        $personnel2 = Personnel::factory()->create(['nom' => 'Martin', 'prenom' => 'Pierre']);

        $response = $this->get(route('personnel.index', ['search' => 'Dupont']));

        $response->assertStatus(200);
        $response->assertSee('Dupont');
        $response->assertSee('Jean');
        $response->assertDontSee('Martin');
    }

    /** @test */
    public function un_utilisateur_peut_rechercher_un_personnel_par_email()
    {
        $personnel1 = Personnel::factory()->create(['email' => 'dupont@example.com']);
        $personnel2 = Personnel::factory()->create(['email' => 'martin@example.com']);

        $response = $this->get(route('personnel.index', ['search' => 'dupont@']));

        $response->assertStatus(200);
        $response->assertSee('dupont@example.com');
        $response->assertDontSee('martin@example.com');
    }

    /** @test */
    public function la_liste_n_affiche_pas_les_personnels_supprimes_par_defaut()
    {
        $actif = Personnel::factory()->create(['nom' => 'Actif']);
        $supprime = Personnel::factory()->create(['nom' => 'Supprime']);
        $supprime->delete();

        $response = $this->get(route('personnel.index'));

        $response->assertSee('Actif');
        $response->assertDontSee('Supprime');
    }

    /** @test */
    public function un_utilisateur_peut_voir_la_liste_des_personnels_supprimes()
    {
        $actif = Personnel::factory()->create(['nom' => 'Actif']);
        $supprime = Personnel::factory()->create(['nom' => 'Supprime']);
        $supprime->delete();

        $response = $this->get(route('personnel.index', ['show_deleted' => true]));

        $response->assertDontSee('Actif');
        $response->assertSee('Supprime');
    }

    /** @test */
    public function un_utilisateur_peut_voir_le_formulaire_de_creation()
    {
        $response = $this->get(route('personnel.create'));

        $response->assertStatus(200);
        $response->assertViewIs('personnel.create');
    }

    /** @test */
    public function un_utilisateur_peut_creer_un_nouveau_personnel()
    {
        $data = [
            'matricule' => 'EMP001',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'telephone' => '0123456789',
            'telephone_mobile' => '0612345678',
            'poste' => 'Tuyauteur',
            'departement' => 'Production',
            'date_embauche' => '2024-01-01',
            'salaire' => 2500.00,
            'adresse' => '123 rue Example',
            'ville' => 'Paris',
            'code_postal' => '75001',
            'numero_securite_sociale' => '123456789012345',
            'statut' => 'actif',
            'notes' => 'Nouveau employé',
        ];

        $response = $this->post(route('personnel.store'), $data);

        $response->assertRedirect(route('personnel.index'));
        $response->assertSessionHas('success', 'Employé créé avec succès.');

        $this->assertDatabaseHas('personnels', [
            'matricule' => 'EMP001',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
        ]);
    }

    /** @test */
    public function la_creation_necessite_des_champs_obligatoires()
    {
        $response = $this->post(route('personnel.store'), []);

        $response->assertSessionHasErrors(['matricule', 'nom', 'prenom', 'email', 'statut']);
    }

    /** @test */
    public function le_matricule_doit_etre_unique()
    {
        Personnel::factory()->create(['matricule' => 'EMP001']);

        $data = Personnel::factory()->make(['matricule' => 'EMP001'])->toArray();

        $response = $this->post(route('personnel.store'), $data);

        $response->assertSessionHasErrors(['matricule']);
    }

    /** @test */
    public function l_email_doit_etre_unique()
    {
        Personnel::factory()->create(['email' => 'test@example.com']);

        $data = Personnel::factory()->make(['email' => 'test@example.com'])->toArray();

        $response = $this->post(route('personnel.store'), $data);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function l_email_doit_etre_valide()
    {
        $data = Personnel::factory()->make(['email' => 'email-invalide'])->toArray();

        $response = $this->post(route('personnel.store'), $data);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function la_date_de_depart_doit_etre_apres_la_date_d_embauche()
    {
        $data = Personnel::factory()->make([
            'date_embauche' => '2024-01-01',
            'date_depart' => '2023-12-31',
        ])->toArray();

        $response = $this->post(route('personnel.store'), $data);

        $response->assertSessionHasErrors(['date_depart']);
    }

    /** @test */
    public function le_salaire_doit_etre_positif()
    {
        $data = Personnel::factory()->make(['salaire' => -1000])->toArray();

        $response = $this->post(route('personnel.store'), $data);

        $response->assertSessionHasErrors(['salaire']);
    }

    /** @test */
    public function le_statut_doit_etre_valide()
    {
        $data = Personnel::factory()->make(['statut' => 'invalide'])->toArray();

        $response = $this->post(route('personnel.store'), $data);

        $response->assertSessionHasErrors(['statut']);
    }

    /** @test */
    public function un_utilisateur_peut_voir_un_personnel()
    {
        $personnel = Personnel::factory()->create();

        $response = $this->get(route('personnel.show', $personnel));

        $response->assertStatus(200);
        $response->assertViewIs('personnel.show');
        $response->assertViewHas('personnel');
        $response->assertSee($personnel->nom);
        $response->assertSee($personnel->prenom);
    }

    /** @test */
    public function un_utilisateur_peut_voir_le_formulaire_d_edition()
    {
        $personnel = Personnel::factory()->create();

        $response = $this->get(route('personnel.edit', $personnel));

        $response->assertStatus(200);
        $response->assertViewIs('personnel.edit');
        $response->assertViewHas('personnel');
    }

    /** @test */
    public function un_utilisateur_peut_modifier_un_personnel()
    {
        $personnel = Personnel::factory()->create([
            'nom' => 'Ancien Nom',
            'prenom' => 'Ancien Prenom',
        ]);

        $data = [
            'matricule' => $personnel->matricule,
            'nom' => 'Nouveau Nom',
            'prenom' => 'Nouveau Prenom',
            'email' => $personnel->email,
            'statut' => $personnel->statut,
        ];

        $response = $this->patch(route('personnel.update', $personnel), $data);

        $response->assertRedirect(route('personnel.index'));
        $response->assertSessionHas('success', 'Employé mis à jour avec succès.');

        $this->assertDatabaseHas('personnels', [
            'id' => $personnel->id,
            'nom' => 'Nouveau Nom',
            'prenom' => 'Nouveau Prenom',
        ]);
    }

    /** @test */
    public function un_utilisateur_peut_supprimer_un_personnel()
    {
        $personnel = Personnel::factory()->create();

        $response = $this->delete(route('personnel.destroy', $personnel));

        $response->assertRedirect(route('personnel.index'));
        $response->assertSessionHas('success', 'Employé supprimé avec succès.');

        $this->assertSoftDeleted('personnels', [
            'id' => $personnel->id,
        ]);
    }

    /** @test */
    public function un_utilisateur_peut_restaurer_un_personnel_supprime()
    {
        $personnel = Personnel::factory()->create();
        $personnel->delete();

        $response = $this->get(route('personnel.restore', $personnel->id));

        $response->assertRedirect(route('personnel.index'));
        $response->assertSessionHas('success', 'Employé restauré avec succès.');

        $this->assertDatabaseHas('personnels', [
            'id' => $personnel->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function un_utilisateur_peut_ajouter_un_conge()
    {
        $personnel = Personnel::factory()->create();

        $data = [
            'date_debut' => '2024-06-01',
            'date_fin' => '2024-06-15',
            'type' => 'conge_paye',
            'motif' => 'Vacances d\'été',
            'statut' => 'valide',
        ];

        $response = $this->post(route('personnel.conges.store', $personnel), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Le congé a été ajouté avec succès.');

        $this->assertDatabaseHas('personnel_conges', [
            'personnel_id' => $personnel->id,
            'date_debut' => '2024-06-01',
            'date_fin' => '2024-06-15',
            'type' => 'conge_paye',
        ]);
    }

    /** @test */
    public function un_conge_ne_peut_pas_chevaucher_un_autre_conge()
    {
        $personnel = Personnel::factory()->create();

        // Créer un congé existant
        PersonnelConge::factory()->create([
            'personnel_id' => $personnel->id,
            'date_debut' => '2024-06-01',
            'date_fin' => '2024-06-15',
        ]);

        // Tenter de créer un congé qui chevauche
        $data = [
            'date_debut' => '2024-06-10',
            'date_fin' => '2024-06-20',
            'type' => 'conge_paye',
            'statut' => 'valide',
        ];

        $response = $this->post(route('personnel.conges.store', $personnel), $data);

        $response->assertSessionHasErrors(['date_debut']);

        $this->assertCount(1, $personnel->fresh()->conges);
    }

    /** @test */
    public function la_date_de_fin_du_conge_doit_etre_apres_la_date_de_debut()
    {
        $personnel = Personnel::factory()->create();

        $data = [
            'date_debut' => '2024-06-15',
            'date_fin' => '2024-06-01',
            'type' => 'conge_paye',
            'statut' => 'valide',
        ];

        $response = $this->post(route('personnel.conges.store', $personnel), $data);

        $response->assertSessionHasErrors(['date_fin']);
    }

    /** @test */
    public function le_type_de_conge_doit_etre_valide()
    {
        $personnel = Personnel::factory()->create();

        $data = [
            'date_debut' => '2024-06-01',
            'date_fin' => '2024-06-15',
            'type' => 'type_invalide',
            'statut' => 'valide',
        ];

        $response = $this->post(route('personnel.conges.store', $personnel), $data);

        $response->assertSessionHasErrors(['type']);
    }

    /** @test */
    public function un_utilisateur_peut_modifier_un_conge()
    {
        $personnel = Personnel::factory()->create();
        $conge = PersonnelConge::factory()->create([
            'personnel_id' => $personnel->id,
            'date_debut' => '2024-06-01',
            'date_fin' => '2024-06-15',
        ]);

        $data = [
            'date_debut' => '2024-07-01',
            'date_fin' => '2024-07-15',
            'type' => 'conge_paye',
            'motif' => 'Vacances modifiées',
            'statut' => 'valide',
        ];

        $response = $this->patch(route('personnel.conges.update', [$personnel, $conge->id]), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Le congé a été mis à jour avec succès.');

        $this->assertDatabaseHas('personnel_conges', [
            'id' => $conge->id,
            'date_debut' => '2024-07-01',
            'date_fin' => '2024-07-15',
        ]);
    }

    /** @test */
    public function un_utilisateur_peut_supprimer_un_conge()
    {
        $personnel = Personnel::factory()->create();
        $conge = PersonnelConge::factory()->create([
            'personnel_id' => $personnel->id,
        ]);

        $response = $this->delete(route('personnel.conges.delete', [$personnel, $conge->id]));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Le congé a été supprimé avec succès.');

        $this->assertDatabaseMissing('personnel_conges', [
            'id' => $conge->id,
        ]);
    }

    /** @test */
    public function l_affichage_d_un_personnel_charge_ses_relations()
    {
        $personnel = Personnel::factory()->create();

        // Créer des congés
        PersonnelConge::factory()->count(3)->create([
            'personnel_id' => $personnel->id,
        ]);

        $response = $this->get(route('personnel.show', $personnel));

        $response->assertStatus(200);
        $this->assertCount(3, $personnel->fresh()->conges);
    }
}

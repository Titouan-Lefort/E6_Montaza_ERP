<?php

namespace Database\Seeders;

use App\Models\Entite;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\PredefinedShortcut;
use App\Models\Role;
use App\Models\SocieteContact;
use App\Models\User;
use App\Models\UserShortcut;
use Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mail;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Permissions list
        $permissions = [
            'gerer_les_utilisateurs' => 'Consulter, Créer, Modifier Et Désactiver Des Utilisateurs',
            'gerer_les_permissions' => 'Consulter, Créer, Modifier Et Supprimer Les Permissions Des Postes',
            'gerer_les_postes' => 'Consulter, Créer, Modifier Et Désactiver Les Postes',
            'voir_historique' => 'Consulter L\'historique Des Modifications Des Données',
            'gerer_les_societes' => 'Créer, Modifier Et Désactiver Les Sociétés',
            'voir_les_societes' => 'Consulter Les Sociétés',
            'gerer_les_contacts' => 'Consulter, Créer, Modifier Et Désactiver Les Contacts',
            'voir_les_matieres' => 'Consulter Les Matières',
            'gerer_les_matieres' => 'Créer, Modifier Et Désactiver Les Matières',
            'voir_les_ddp_et_cde' => 'Consulter Les Demandes De Prix Et Les Commandes',
            'gerer_les_ddp_et_cde' => 'Créer, Modifier Et Désactiver Les Demandes De Prix Et Les Commandes',
            'gerer_mail_templates' => 'Modifier Les Modèles De Mail',
            'gerer_info_entreprise' => 'Modifier Les Informations De L\'entreprise',
            'gerer_les_donnees_de_reference' => 'Gérer les données de référence',
            'gerer_les_medias' => 'Gérer les médias',
            'gerer_l_application' => 'Gérer l\'application',
            'voir_les_affaires' => 'Consulter les affaires',
            'voir_la_production' => 'Consulter la production',
            'voir_les_reparations' => 'Consulter les réparations',
            'gerer_les_affaires' => 'Gérer les affaires',
            'gerer_les_factures_reparations' => 'Gérer les factures de réparations',
            'gerer_les_reparations' => 'Gérer les réparations',
            'gerer_le_materiel' => 'Gérer le matériel',
            'voir_les_devis' => 'Consulter les devis',
            'gerer_les_devis' => 'Gérer les devis',
        ];
        foreach ($permissions as $permission => $description) {
            Permission::factory()->create([
            'name' => $permission,
            'description' => $description,
            ]);
        }

        $this->call(EntiteSeeder::class);

        $Gerant = Role::factory()->create([
            'name' => 'Gérant',
            'entite_id' => 1,
            'undeletable' => true,
        ]);
        $Gerant->permissions()->sync(Permission::all());

        Role::factory()->create([
            'name' => 'Responsable Ressources Humaines',
            'entite_id' => 1,
        ]);

        Role::factory()->create([
            'name' => 'Secrétaire',
            'entite_id' => 1,
        ]);
        Role::factory()->create([
            'name' => 'Magasinier',
            'entite_id' => 1,
        ]);
        Role::factory()->create([
            'name' => 'Chef d\'équipe',
            'entite_id' => 2,
        ]);
        Role::factory()->create([
            'name' => 'Assistant Technique',
            'entite_id' => 3,
        ]);
        Role::factory()->create([
            'name' => 'Assistante chargée d\'affaires',
            'entite_id' => 1,
        ]);

        User::factory()->create([
            'last_name' => 'Admin',
            'first_name' => 'Admin',
            'phone' => '0600000000',
            'email' => 'admin@atlantismontaza.fr',
            'password' => Hash::make('Not24get'),
            'role_id' => 1,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        User::factory()->create([
            'last_name' => 'JOSIPOVIC',
            'first_name' => 'Goran',
            'phone' => '06 88 84 88 53',
            'email' => 'goran.josipovic@atlantismontaza.fr',
            'password' => Hash::make('Gjosipovic'.date('Y')),
            'role_id' => 1,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'NICOL',
            'first_name' => 'Sylvie',
            'phone' => '02 40 17 65 45',
            'email' => 'sylvie.nicol@atlantismontaza.fr',
            'password' => Hash::make('Snicol'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 2,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'AGASSE',
            'first_name' => 'Janie',
            'phone' => '02 40 17 65 62',
            'email' => 'janie.agasse@atlantismontaza.fr',
            'password' => Hash::make('Jagasse'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 3,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'AVRAMOV',
            'first_name' => 'Stefan',
            'phone' => '06 76 81 08 82',
            'email' => 'stefan.avramov@amb.bg',
            'password' => Hash::make('Savramov'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 6,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'BIMBO',
            'first_name' => 'Harri',
            'phone' => '06 30 31 32 13',
            'email' => 'harry.bimbo@atlantisventilation.fr',
            'password' => Hash::make('Hbimbo'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 5,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        User::factory()->create([
            'last_name' => 'EVANNO',
            'first_name' => 'Mélanie',
            'phone' => '06 30 31 32 13',
            'email' => 'melanie.evanno@atlantismontaza.fr',
            'password' => Hash::make('Mevanno'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 7,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        // Notification::factory()->times(100)->create();
        $this->call(PaysSeeder::class);
        $this->call(FormeJuridiqueSeeder::class);
        $this->call(CodeApeSeeder::class);
        $this->call(SocieteTypeSeeder::class);
        $this->call(ConditionPaiementSeeder::class);
        // SocieteContact::factory()->times(100)->create();
        // $this->call(SocieteProductionSeeder::class);
        $this->call(PredefinedShortcutsSeeder::class);
        foreach (PredefinedShortcut::all() as $shortcut) {
            UserShortcut::create([
                'user_id' => 1,
                'shortcut_id' => $shortcut->id,
            ]);
        }
        $this->call(UniteSeeder::class);
        $this->call(FamilleSeeder::class);
        $this->call(SousFamilleSeeder::class);
        $this->call(StandardSeeder::class);
        // $this->call(MaterialSeeder::class);
        // $this->call(MatiereSeeder::class);
        $this->call(DdpCdeStatutSeeder::class);
        // $this->call(DdpSeeder::class);
        $this->call(MailTemplateSeeder::class);
        $this->call(TypeExpeditionSeeder::class);
        // $this->call(CdeSeeder::class);
        // $this->call([
        //     ProductionSeeder::class,
        //     PersonnelSeeder::class,
        // ]);
    }
}

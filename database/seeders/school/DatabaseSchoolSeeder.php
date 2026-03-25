<?php

namespace Database\Seeders\school;


use App\Models\Entite;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\PredefinedShortcut;
use App\Models\Role;
use App\Models\SocieteContact;
use App\Models\User;
use App\Models\UserShortcut;
use Database\Seeders\CdeNoteSeeder;
use Database\Seeders\CodeApeSeeder;
use Database\Seeders\ConditionPaiementSeeder;
use Database\Seeders\DdpCdeStatutSeeder;
use Database\Seeders\EntiteSeeder;
use Database\Seeders\FamilleSeeder;
use Database\Seeders\FormeJuridiqueSeeder;
use Database\Seeders\MailTemplateSeeder;
use Database\Seeders\MaterialSeeder;
use Database\Seeders\MatiereProductionSeeder;
use Database\Seeders\PaysSeeder;
use Database\Seeders\PredefinedShortcutsSeeder;
use Database\Seeders\school\SocieteSchoolSeeder;
use Database\Seeders\SocieteTypeSeeder;
use Database\Seeders\SousFamilleSeeder;
use Database\Seeders\StandardSeeder;
use Database\Seeders\TypeExpeditionSeeder;
use Database\Seeders\UniteSeeder;
use Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mail;

class DatabaseSchoolSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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
        ];
        foreach ($permissions as $permission => $description) {
            Permission::factory()->create([
            'name' => $permission,
            'description' => $description,
            ]);
        }
        // User::factory(10)->create();
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
            'last_name' => 'DOE',
            'first_name' => 'John',
            'phone' => '06 00 34 56 78',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('Jdoe'.date('Y')),
            'role_id' => 1,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'SMITH',
            'first_name' => 'Jane',
            'phone' => '06 00 65 43 21',
            'email' => 'jane.smith@example.com',
            'password' => Hash::make('Jsmith'.date('Y')),
            'role_id' => 2,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'BROWN',
            'first_name' => 'Charlie',
            'phone' => '06 00 76 54 32',
            'email' => 'charlie.brown@example.com',
            'password' => Hash::make('Cbrown'.date('Y')),
            'role_id' => 3,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'JOHNSON',
            'first_name' => 'Emily',
            'phone' => '06 00 67 89 01',
            'email' => 'emily.johnson@example.com',
            'password' => Hash::make('Ejohnson'.date('Y')),
            'role_id' => 6,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'WILLIAMS',
            'first_name' => 'Harry',
            'phone' => '06 00 45 67 89',
            'email' => 'harry.williams@example.com',
            'password' => Hash::make('Hwilliams'.date('Y')),
            'role_id' => 5,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'DAVIS',
            'first_name' => 'Sophia',
            'phone' => '06 00 56 78 90',
            'email' => 'sophia.davis@example.com',
            'password' => Hash::make('Sdavis'.date('Y')),
            'role_id' => 7,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $temps = now();
        // Notification::factory()->times(100)->create();
        echo "Les utilisateurs ont été créés avec succès en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(PaysSeeder::class);
        echo "Les pays ont été créés avec succès en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(FormeJuridiqueSeeder::class);
        echo "Les formes juridiques à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(CodeApeSeeder::class);
        echo "Les codes APE ont été créés avec succès en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(SocieteTypeSeeder::class);
        echo "Les types de société ont été créés avec succès en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(ConditionPaiementSeeder::class);
        echo "Les conditions de paiement à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        // SocieteContact::factory()->times(100)->create();
        // $this->call(SocieteSchoolSeeder::class);
        // echo "Les sociétés à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(PredefinedShortcutsSeeder::class);
        echo "Les raccourcis prédéfinis ont été créés avec succès en " . $temps->diffInSeconds(now()) . " secondes.\n";
        foreach (PredefinedShortcut::all() as $shortcut) {
            UserShortcut::create([
            'user_id' => 1,
            'shortcut_id' => $shortcut->id,
            ]);
        }
        echo "raccourcis utilisateur à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(UniteSeeder::class);
        echo "unités à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(FamilleSeeder::class);
        echo "familles à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(SousFamilleSeeder::class);
        echo "sous-familles à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(StandardSeeder::class);
        echo "standards à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        // $this->call(MaterialSeeder::class);
        // echo "matériaux à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        // $this->call(MatiereProductionSeeder::class);
        // echo "matières de production à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        // $this->call(MatiereSeeder::class);
        $this->call(DdpCdeStatutSeeder::class);
        echo "statuts des DDP et CDE à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        // $this->call(DdpSeeder::class);
        $this->call(MailTemplateSeeder::class);
        echo "modèles de mail à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        $this->call(TypeExpeditionSeeder::class);
        echo "types d'expédition à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
        // $this->call(CdeSeeder::class);
        // $this->call(CdeNoteSeeder::class);
        // echo "notes de commande à été remplie en " . $temps->diffInSeconds(now()) . " secondes.\n";
    }
}


<?php

namespace Database\Factories;

use App\Models\Commentaire;
use App\Models\ConditionPaiement;
use App\Models\DdpCdeStatut;
use App\Models\Entite;
use App\Models\SocieteContact;
use App\Models\TypeExpedition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cde>
 */
class CdeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = 'CDE-' . date('y'). '-' . $this->faker->numberBetween(1000, 9999);
        $random_statut = DdpCdeStatut::all()->random();
        $random_user = User::all()->random();
        $random_user1 = User::all()->random();
        $random_entite = Entite::all()->random();
        $random_societe_contact = SocieteContact::all()->random();
        $random_user2 = User::all()->random();
        $random_type_expedition = TypeExpedition::all()->random();
        $adresse['adresse'] = $random_entite->adresse;
        $adresse['code_postal'] = $random_entite->code_postal;
        $adresse['ville'] = $random_entite->ville;
        $adresse['pays'] = 'France';
        $adresse = json_encode($adresse);
        $random_condition_paiement = ConditionPaiement::all()->random();
        return [
            'code' => $code,
            'nom' => $this->faker->sentence(3),
            'ddp_cde_statut_id' => $random_statut,
            'user_id' => $random_user,
            'entite_id' => $random_entite,
            'ddp_id' => null,
            'devis_numero' => $this->faker->optional()->numerify('DEV-###'),
            'affaire_suivi_par_id' => $random_user1,
            'acheteur_id' => $random_user2,
            'tva' => $this->faker->numberBetween(0, 20),
            'type_expedition_id' => $random_type_expedition,
            'adresse_livraison' => $adresse,
            'adresse_facturation' => $adresse,
            'condition_paiement_id' => $random_condition_paiement,
            'afficher_destinataire' => $this->faker->boolean,
            'commentaire_id' => Commentaire::create(['contenu' => $this->faker->paragraph])->id,
        ];
    }
}

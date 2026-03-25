<?php

namespace Database\Factories;

use App\Models\DdpCdeStatut;
use App\Models\DdpLigne;
use App\Models\Societe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DdpLigneFournisseur>
 */
class DdpLigneFournisseurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $random_ddp_ligne = DdpLigne::all()->random();
        $random_societe = Societe::all()->random();
        $random_statut = DdpCdeStatut::whereIn('id', [2, 3, 4])->get()->random();
        return [
            'ddp_ligne_id' => $random_ddp_ligne->id,
            'societe_id' => $random_societe->id,
            'ddp_cde_statut_id' => $random_statut->id,
        ];
    }
}

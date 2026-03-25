<?php

namespace Database\Seeders;

use App\Models\ConditionPaiement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConditionPaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConditionPaiement::create(['nom' => '30 jours']);
        ConditionPaiement::create(['nom' => '30 jours FDM']);
        ConditionPaiement::create(['nom' => '30 jours le 10']);
        ConditionPaiement::create(['nom' => '30 jours le 15']);
        ConditionPaiement::create(['nom' => '45 jours']);
        ConditionPaiement::create(['nom' => '45 jours, FDM']);
        ConditionPaiement::create(['nom' => '60 jours FDM']);
        ConditionPaiement::create(['nom' => '60 jours net']);
        ConditionPaiement::create(['nom' => 'Comptant']);
        ConditionPaiement::create(['nom' => 'LCR 30 jours FDM']);
        ConditionPaiement::create(['nom' => 'LCR 45 jours']);
    }
}

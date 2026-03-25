<?php

namespace Database\Seeders;

use App\Models\Ddp;
use App\Models\DdpLigne;
use App\Models\DdpLigneFournisseur;
use Database\Factories\DdpFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DdpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ddp::factory(10)->create();
        DdpLigne::factory(35)->create();
        DdpLigneFournisseur::factory(60)->create();
    }
}

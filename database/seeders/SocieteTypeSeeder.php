<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocieteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('societe_types')->insert([
            ['nom' => 'Client'],
            ['nom' => 'Fournisseur'],
            ['nom' => 'Client et Fournisseur'],
        ]);
    }
}

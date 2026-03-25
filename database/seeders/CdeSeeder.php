<?php

namespace Database\Seeders;

use App\Models\Cde;
use App\Models\CdeLigne;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CdeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cde::factory()->count(10)->create();
        CdeLigne::factory()->count(30)->create();
    }
}

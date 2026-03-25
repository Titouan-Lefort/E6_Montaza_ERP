<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            'Acier', 'PE', 'PB', 'Inox', 'PVC', 'Cuivre', 'Teflon', 'Inox SuperDuplex'
        ])->each(fn($material) => Material::factory()->create(['nom' => $material]));
    }
}

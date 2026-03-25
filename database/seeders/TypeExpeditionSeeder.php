<?php

namespace Database\Seeders;

use App\Models\TypeExpedition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeExpedition::create(['nom' => 'Livraison', 'short' => 'livraison']);
        TypeExpedition::create(['nom' => 'Enlèvement par nos soins', 'short' => 'enlèvement']);
    }
}

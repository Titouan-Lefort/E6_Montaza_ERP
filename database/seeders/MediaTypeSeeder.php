<?php

namespace Database\Seeders;

use App\Models\MediaType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MediaType::insert([
            [
            'nom' => 'Aucun',
            'background_color_light' => '#f3f4f6',
            'background_color_dark' => '#374151',
            'text_color_light' => '#111827',
            'text_color_dark' => '#f9fafb',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'nom' => 'Non-conformité',
            'background_color_light' => '#fee2e2',
            'background_color_dark' => '#991b1b',
            'text_color_light' => '#991b1b',
            'text_color_dark' => '#fee2e2',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'nom' => 'Problème livraison',
            'background_color_light' => '#fef9c3',
            'background_color_dark' => '#92400e',
            'text_color_light' => '#92400e',
            'text_color_dark' => '#fef9c3',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'nom' => 'Document administratif',
            'background_color_light' => '#dbeafe',
            'background_color_dark' => '#1e40af',
            'text_color_light' => '#1e40af',
            'text_color_dark' => '#dbeafe',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'nom' => 'Justificatif',
            'background_color_light' => '#bbf7d0',
            'background_color_dark' => '#166534',
            'text_color_light' => '#166534',
            'text_color_dark' => '#bbf7d0',
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTables extends Command
{
    protected $signature = 'db:truncate-tables';
    protected $description = 'Vide toutes les tables de la base de données';

    public function handle(): void
    {
        // Désactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_" . env('DB_DATABASE')}; // Récupère le nom de la table

            // Sauter la table migrations
            if ($tableName === 'migrations') {
                continue;
            }

            DB::table($tableName)->truncate();
            $this->info("Table '{$tableName}' vidée.");
        }

        // Réactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Toutes les tables ont été vidées.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDatabase extends Command
{
    protected $signature = 'db:reset-database';
    protected $description = 'supprime toutes les tables de la base de données et les re créer';

    public function handle(): void
    {
        // Désactiver les contraintes de clé étrangère
        DB::statement('SET session_replication_role = replica;');
        $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
        $depart = microtime(true);
        // Supprimer toutes les tables
        foreach ($tables as $table) {
            $tableName = $table->tablename; // Récupère le nom de la table
            DB::statement("DROP TABLE IF EXISTS {$tableName} CASCADE");
            $this->info("Table '{$tableName}' supprimée.");
        }

        // Réactiver les contraintes de clé étrangère
        DB::statement('SET session_replication_role = DEFAULT;');

        $this->info('Toutes les tables ont été supprimées.');
        $this->call('migrate');
        $this->info('Toutes les tables ont été recréées.');
        $this->call('db:seed');
        $fin = microtime(true);
        $this->info('Toutes les tables ont été remplies avec des données.');
        $this->info('Temps d\'exécution : ' . round($fin - $depart, 3) . ' secondes.');
    }
}

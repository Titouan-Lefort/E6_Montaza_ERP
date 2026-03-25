<?php

namespace App\Console\Commands;

use App\Models\Personnel;
use App\Models\PersonnelConge;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdatePersonnelStatut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'personnel:update-statut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour automatiquement le statut des personnels selon leurs congés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $updated = 0;

        // Récupérer tous les personnels actifs ou en congé
        $personnels = Personnel::whereIn('statut', ['actif', 'en_conge'])->get();

        foreach ($personnels as $personnel) {
            $oldStatut = $personnel->statut;

            // Vérifier si le personnel a un congé validé en cours
            $hasActiveConge = $personnel->conges()
                ->where('statut', 'valide')
                ->where('date_debut', '<=', $today)
                ->where('date_fin', '>=', $today)
                ->exists();

            // Mettre à jour le statut selon la situation
            if ($hasActiveConge && $personnel->statut !== 'en_conge') {
                $personnel->statut = 'en_conge';
                $personnel->save();
                $updated++;
                $this->info("Personnel {$personnel->nom} {$personnel->prenom} passé en congé");
            } elseif (!$hasActiveConge && $personnel->statut === 'en_conge') {
                $personnel->statut = 'actif';
                $personnel->save();
                $updated++;
                $this->info("Personnel {$personnel->nom} {$personnel->prenom} repassé à actif");
            }
        }

        $this->info("✓ {$updated} statut(s) mis à jour");

        return Command::SUCCESS;
    }
}

<?php

namespace App\Observers;

use App\Models\PersonnelConge;
use Carbon\Carbon;

class PersonnelCongeObserver
{
    /**
     * Handle the PersonnelConge "created" event.
     */
    public function created(PersonnelConge $conge): void
    {
        $this->updatePersonnelStatut($conge);
    }

    /**
     * Handle the PersonnelConge "updated" event.
     */
    public function updated(PersonnelConge $conge): void
    {
        $this->updatePersonnelStatut($conge);
    }

    /**
     * Handle the PersonnelConge "deleted" event.
     */
    public function deleted(PersonnelConge $conge): void
    {
        $this->updatePersonnelStatut($conge);
    }

    /**
     * Met à jour le statut du personnel selon ses congés
     */
    private function updatePersonnelStatut(PersonnelConge $conge): void
    {
        $personnel = $conge->personnel;

        // Ne pas modifier si le personnel n'est pas actif ou en congé
        if (!in_array($personnel->statut, ['actif', 'en_conge'])) {
            return;
        }

        $today = Carbon::today();

        // Vérifier si le personnel a un congé validé en cours
        $hasActiveConge = $personnel->conges()
            ->where('statut', 'valide')
            ->where('date_debut', '<=', $today)
            ->where('date_fin', '>=', $today)
            ->exists();

        // Mettre à jour le statut
        if ($hasActiveConge && $personnel->statut !== 'en_conge') {
            $personnel->statut = 'en_conge';
            $personnel->saveQuietly(); // Utiliser saveQuietly pour éviter les événements en cascade
        } elseif (!$hasActiveConge && $personnel->statut === 'en_conge') {
            $personnel->statut = 'actif';
            $personnel->saveQuietly();
        }
    }
}

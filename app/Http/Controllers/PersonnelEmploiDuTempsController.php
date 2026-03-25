<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\AffairePersonnel;
use App\Models\AffairePersonnelTache;
use App\Models\PersonnelConge;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PersonnelEmploiDuTempsController extends Controller
{
    /**
     * Affiche l'emploi du temps d'un personnel
     */
    public function index(Request $request, Personnel $personnel)
    {
        // Navigation par semaine
        $weekOffset = (int) $request->get('week', 0);

        // Calculer la semaine courante (lundi à vendredi)
        $startOfWeek = now()->addWeeks($weekOffset)->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(4); // Vendredi

        // Générer les jours de la semaine (lundi à vendredi)
        $weekDays = [];
        $tempDate = $startOfWeek->copy();
        for ($i = 0; $i < 5; $i++) {
            $weekDays[] = $tempDate->copy();
            $tempDate->addDay();
        }

        // Heures de la journée (8h à 18h)
        $hours = range(8, 18);

        // Récupérer toutes les assignations avec les tâches
        $assignations = AffairePersonnel::with(['affaire', 'taches'])
            ->where('personnel_id', $personnel->id)
            ->get();

        // Mettre à jour automatiquement les statuts des tâches
        $this->updateTachesStatuts($assignations);

        // Récupérer les congés validés pour la semaine
        $conges = $personnel->conges()
            ->where('statut', 'valide')
            ->where(function($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('date_debut', [$startOfWeek, $endOfWeek])
                    ->orWhereBetween('date_fin', [$startOfWeek, $endOfWeek])
                    ->orWhere(function($q) use ($startOfWeek, $endOfWeek) {
                        $q->where('date_debut', '<=', $startOfWeek)
                          ->where('date_fin', '>=', $endOfWeek);
                    });
            })
            ->get();

        // Organiser les événements par date et créneau (matin/après-midi)
        $evenementsByDateTime = [];

        foreach ($assignations as $assignation) {
            if ($assignation->affaire) {
                // Événements pour les tâches
                foreach ($assignation->taches as $tache) {
                    $tacheStartDate = $tache->date_debut;
                    $tacheEndDate = $tache->date_fin ?? $tacheStartDate;

                    $creneauDebut = $tache->creneau_debut ?? 'matin';
                    $creneauFin = $tache->creneau_fin ?? 'apres_midi';

                    // Pour chaque jour de la semaine
                    foreach ($weekDays as $day) {
                        if ($day->between($tacheStartDate->startOfDay(), $tacheEndDate->endOfDay())) {
                            $dateKey = $day->format('Y-m-d');

                            // Déterminer les créneaux à afficher pour ce jour
                            $isFirstDay = $day->format('Y-m-d') == $tacheStartDate->format('Y-m-d');
                            $isLastDay = $day->format('Y-m-d') == $tacheEndDate->format('Y-m-d');

                            // Liste des créneaux à traiter pour ce jour
                            $creneauxATraiter = [];

                            if ($isFirstDay && $isLastDay) {
                                // Même jour - utiliser exactement les créneaux définis
                                if ($creneauDebut == 'matin') {
                                    $creneauxATraiter[] = ['heure' => 8, 'creneau' => 'matin', 'debut' => 8, 'fin' => 11];
                                }
                                if ($creneauFin == 'apres_midi') {
                                    $creneauxATraiter[] = ['heure' => 13, 'creneau' => 'apres_midi', 'debut' => 13, 'fin' => 16];
                                }
                            } elseif ($isFirstDay) {
                                // Premier jour - à partir du créneau de début
                                if ($creneauDebut == 'matin') {
                                    $creneauxATraiter[] = ['heure' => 8, 'creneau' => 'matin', 'debut' => 8, 'fin' => 11];
                                    $creneauxATraiter[] = ['heure' => 13, 'creneau' => 'apres_midi', 'debut' => 13, 'fin' => 16];
                                } else {
                                    $creneauxATraiter[] = ['heure' => 13, 'creneau' => 'apres_midi', 'debut' => 13, 'fin' => 16];
                                }
                            } elseif ($isLastDay) {
                                // Dernier jour - jusqu'au créneau de fin
                                if ($creneauFin == 'apres_midi') {
                                    $creneauxATraiter[] = ['heure' => 8, 'creneau' => 'matin', 'debut' => 8, 'fin' => 11];
                                    $creneauxATraiter[] = ['heure' => 13, 'creneau' => 'apres_midi', 'debut' => 13, 'fin' => 16];
                                } else {
                                    $creneauxATraiter[] = ['heure' => 8, 'creneau' => 'matin', 'debut' => 8, 'fin' => 11];
                                }
                            } else {
                                // Jours intermédiaires - toute la journée
                                $creneauxATraiter[] = ['heure' => 8, 'creneau' => 'matin', 'debut' => 8, 'fin' => 11];
                                $creneauxATraiter[] = ['heure' => 13, 'creneau' => 'apres_midi', 'debut' => 13, 'fin' => 16];
                            }

                            // Ajouter les événements pour chaque créneau
                            foreach ($creneauxATraiter as $creneau) {
                                $timeKey = $dateKey . '-' . $creneau['heure'];
                                if (!isset($evenementsByDateTime[$timeKey])) {
                                    $evenementsByDateTime[$timeKey] = [];
                                }

                                $evenementsByDateTime[$timeKey][] = [
                                    'type' => 'tache',
                                    'titre' => $tache->titre,
                                    'statut' => $tache->statut,
                                    'priorite' => $tache->priorite,
                                    'affaire_code' => $assignation->affaire->code,
                                    'affaire_titre' => $assignation->affaire->nom,
                                    'affaire_id' => $assignation->affaire->id,
                                    'tache_id' => $tache->id,
                                    'url' => route('affaires.personnel.taches', [$assignation->affaire, $personnel]),
                                    'heure_debut' => $creneau['debut'],
                                    'heure_fin' => $creneau['fin'],
                                    'duree' => 3,
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Ajouter les congés dans l'emploi du temps
        foreach ($conges as $conge) {
            $congeStartDate = $conge->date_debut;
            $congeEndDate = $conge->date_fin;

            foreach ($weekDays as $day) {
                if ($day->between($congeStartDate->startOfDay(), $congeEndDate->endOfDay())) {
                    $dateKey = $day->format('Y-m-d');

                    // Les congés occupent toute la journée (matin et après-midi)
                    foreach ([8, 13] as $heure) {
                        $timeKey = $dateKey . '-' . $heure;
                        if (!isset($evenementsByDateTime[$timeKey])) {
                            $evenementsByDateTime[$timeKey] = [];
                        }

                        $typeLabel = match($conge->type) {
                            'conge_paye' => 'Congé payé',
                            'conge_maladie' => 'Congé maladie',
                            'conge_sans_solde' => 'Sans solde',
                            default => 'Congé'
                        };

                        $evenementsByDateTime[$timeKey][] = [
                            'type' => 'conge',
                            'titre' => $typeLabel,
                            'conge_type' => $conge->type,
                            'motif' => $conge->motif,
                            'heure_debut' => $heure,
                            'heure_fin' => $heure == 8 ? 11 : 16,
                            'duree' => 3,
                        ];
                    }
                }
            }
        }

        // Statistiques
        $tachesTotales = 0;
        $tachesTerminees = 0;
        $tachesEnCours = 0;
        foreach($assignations as $assignation) {
            $tachesTotales += $assignation->taches->count();
            $tachesTerminees += $assignation->taches->where('statut', 'termine')->count();
            $tachesEnCours += $assignation->taches->where('statut', 'en_cours')->count();
        }

        $stats = [
            'affaires' => $assignations->count(),
            'taches_totales' => $tachesTotales,
            'taches_en_cours' => $tachesEnCours,
            'taches_terminees' => $tachesTerminees,
        ];

        return view('personnel.emploi-du-temps', compact(
            'personnel',
            'weekDays',
            'hours',
            'weekOffset',
            'startOfWeek',
            'endOfWeek',
            'stats',
            'evenementsByDateTime'
        ));
    }

    /**
     * Met à jour automatiquement le statut des tâches en fonction de la date
     */
    private function updateTachesStatuts($assignations)
    {
        $today = now()->startOfDay();

        foreach ($assignations as $assignation) {
            // Récupérer toutes les tâches qui ne sont pas terminées
            $taches = $assignation->taches()->whereIn('statut', ['a_faire', 'en_cours'])->get();

            foreach ($taches as $tache) {
                $dateDebut = $tache->date_debut->startOfDay();

                // Si la date de début est atteinte ou dépassée et que la tâche est "à faire", la passer en "en cours"
                if ($dateDebut->lte($today) && $tache->statut === 'a_faire') {
                    $tache->update(['statut' => 'en_cours']);
                }
            }
        }
    }
}

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Mise à jour automatique du statut des personnels selon leurs congés
Schedule::command('personnel:update-statut')
    ->daily()
    ->at('00:05')
    ->description('Met à jour le statut des personnels selon leurs congés');

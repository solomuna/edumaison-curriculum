<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:backup-database')->dailyAt('01:45')->withoutOverlapping()->onOneServer();
Schedule::command('app:backup-database --check')->dailyAt('06:15')->withoutOverlapping()->onOneServer();

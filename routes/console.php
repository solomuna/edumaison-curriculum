<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\FamilyRevisionService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:backup-database')->dailyAt('01:45')->withoutOverlapping()->onOneServer();
Schedule::command('app:backup-database --check')->dailyAt('06:15')->withoutOverlapping()->onOneServer();

Artisan::command('family:trigger-due-revisions', function (FamilyRevisionService $service) {
    $settings = DB::table('family_revision_settings as s')->join('households as h', 'h.id', '=', 's.household_id')
        ->where('s.enabled', true)->get(['s.*', 'h.timezone']);
    foreach ($settings as $setting) {
        $now = CarbonImmutable::now($setting->timezone ?: 'Africa/Douala');
        if (substr($setting->revision_time, 0, 5) !== $now->format('H:i') || $setting->last_triggered_on === $now->toDateString()) continue;
        try {
            $service->trigger((int) $setting->household_id, json_decode($setting->child_ids ?? '[]', true));
            DB::table('family_revision_settings')->where('id', $setting->id)
                ->update(['last_triggered_on' => $now->toDateString(), 'last_triggered_at' => now(), 'updated_at' => now()]);
            $this->info('Révision déclenchée pour le foyer '.$setting->household_id);
        } catch (Throwable $e) {
            report($e);
            $this->error('Foyer '.$setting->household_id.' : '.$e->getMessage());
        }
    }
})->purpose('Déclenche les révisions automatiques dues, isolées par foyer');

Schedule::command('family:trigger-due-revisions')->everyMinute()->withoutOverlapping();

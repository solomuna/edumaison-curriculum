<?php

namespace App\Console\Commands;

use App\Support\AttemptHealth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Vérifie chaque heure que les tentatives des enfants s'enregistrent.
 * En cas de panne : erreur critique dans les journaux, et notification si
 * ALERT_WEBHOOK_URL (ex. https://ntfy.sh/<sujet>) et/ou ALERT_EMAIL sont définis.
 * Une même alerte n'est pas répétée plus d'une fois toutes les 6 heures.
 */
class AttemptHealthCommand extends Command
{
    protected $signature = 'app:attempts-health {--hours=2 : Période observée} {--min=5 : Refus minimum pour alerter}';

    protected $description = 'Alerte si les tentatives des enfants sont refusées en série';

    public function handle(): int
    {
        $hours = max(1, (int) $this->option('hours'));
        $rejections = AttemptHealth::rejectionsSince($hours);
        $saved = DB::table('exercise_attempts')->where('created_at', '>=', now()->subHours($hours))->count();
        $rejected = count($rejections);

        $this->line("Tentatives sur {$hours} h : {$saved} enregistrée(s), {$rejected} refusée(s).");

        if (! AttemptHealth::isFailing($saved, $rejected, max(1, (int) $this->option('min')))) {
            return self::SUCCESS;
        }

        $reasons = collect($rejections)->countBy('reason')->sortDesc()->take(3)
            ->map(fn ($n, $reason) => "{$n}× {$reason}")->implode(' ; ');
        $exercises = collect($rejections)->pluck('exercise_id')->filter()->unique()->take(10)->implode(', ');
        $message = "EduMaison : {$rejected} tentative(s) refusée(s) et {$saved} enregistrée(s) sur {$hours} h. "
            ."Causes : {$reasons}. Exercices : {$exercises}.";

        Log::critical('attempts_health_failing', ['saved' => $saved, 'rejected' => $rejected, 'reasons' => $reasons, 'exercises' => $exercises]);
        $this->error($message);

        if (Cache::add('attempt_health:alerted', true, now()->addHours(6))) {
            $this->notify($message);
        }

        return self::FAILURE;
    }

    private function notify(string $message): void
    {
        $webhook = (string) config('services.alerts.webhook');
        if ($webhook !== '') {
            try {
                Http::timeout(10)->withHeaders(['Title' => 'EduMaison : enregistrements en echec', 'Priority' => 'high'])
                    ->withBody($message, 'text/plain')->post($webhook);
            } catch (\Throwable $exception) {
                Log::error('attempts_health_webhook_failed', ['error' => $exception->getMessage()]);
            }
        }

        $email = (string) config('services.alerts.email');
        if ($email !== '') {
            try {
                Mail::raw($message, fn ($mail) => $mail->to($email)->subject('EduMaison : enregistrements en échec'));
            } catch (\Throwable $exception) {
                Log::error('attempts_health_email_failed', ['error' => $exception->getMessage()]);
            }
        }
    }
}

<?php

namespace App\Jobs;

use App\Models\Exercise;
use App\Services\StatementVoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

/** Prépare en arrière-plan les énoncés d'un exercice ouvert, dans la limite du plafond mensuel. */
class GenerateStatementVoice implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 2;
    public int $backoff = 120;

    public function __construct(public int $exerciseId) {}

    public function uniqueId(): string
    {
        return (string) $this->exerciseId;
    }

    public function handle(StatementVoice $voice): void
    {
        $cap = (int) config('services.elevenlabs.statement_monthly_chars');
        $remaining = $cap - $voice->charactersThisMonth();
        $exercise = Exercise::query()->with('lesson.unit.integratedTheme.subject')->find($this->exerciseId);
        if ($exercise && $remaining > 0 && $voice->configured()) $voice->generateFor($exercise, $remaining);
    }
}

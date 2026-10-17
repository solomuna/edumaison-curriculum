<?php

namespace App\Console\Commands;

use App\Models\Exercise;
use App\Services\StatementVoice;
use Illuminate\Console\Command;

/**
 * Prépare les énoncés lus par Mama Judi pour des niveaux donnés.
 *
 *   php artisan app:voice-statements --level="Class 3" --dry-run
 *       compte les caractères à générer, sans rien dépenser
 *   php artisan app:voice-statements --level="Class 3" --level="Class 5" --max-chars=20000
 *       génère jusqu'au budget indiqué (les énoncés déjà prêts sont gratuits)
 *   php artisan app:voice-statements --level="Class 5" --sample=10 --model=eleven_flash_v2_5
 *       10 énoncés d'essai avec un autre modèle, pour comparer à l'écoute
 */
class VoiceStatementsCommand extends Command
{
    protected $signature = 'app:voice-statements
        {--level=* : Niveau(x), ex. "Class 3"}
        {--max-chars=0 : Budget de caractères pour ce lancement}
        {--model= : Modèle ElevenLabs (par défaut ELEVENLABS_STATEMENT_MODEL)}
        {--sample=0 : Ne traite que N énoncés (essai d\'écoute)}
        {--dry-run : Compte sans générer}';

    protected $description = 'Prépare les énoncés lus par Mama Judi (ElevenLabs), dans un budget de caractères';

    public function handle(StatementVoice $voice): int
    {
        $levels = (array) $this->option('level');
        if ($levels === []) {
            $this->error('Indique au moins un niveau : --level="Class 3"');
            return self::FAILURE;
        }
        $dryRun = (bool) $this->option('dry-run');
        $sample = (int) $this->option('sample');
        $model = $this->option('model') ?: null;
        $budget = $dryRun ? PHP_INT_MAX : (int) $this->option('max-chars');
        if (! $dryRun && $budget <= 0) {
            $this->error('Indique un budget : --max-chars=20000 (ou --dry-run pour compter).');
            return self::FAILURE;
        }
        if (! $dryRun && ! $voice->configured()) {
            $this->error('ElevenLabs n\'est pas configuré (ELEVENLABS_API_KEY, ELEVENLABS_VOICE_ID).');
            return self::FAILURE;
        }

        $exercises = Exercise::query()
            ->with('lesson.unit.integratedTheme.subject.level')
            ->where('is_active', true)
            ->whereHas('lesson.unit.integratedTheme.subject.level', fn ($q) => $q->whereIn('name', $levels))
            ->orderBy('id')
            ->get();

        $created = 0;
        $spent = 0;
        $left = 0;
        $samples = [];
        foreach ($exercises as $exercise) {
            if ($sample > 0 && $created >= $sample) break;
            $remaining = $budget - $spent;
            if ($sample > 0) $remaining = min($remaining, 10_000);
            [$c, $s, $l] = $voice->generateFor($exercise, $remaining, $model, $dryRun);
            $created += $c;
            $spent += $s;
            $left += $l;
            if ($c > 0 && $sample > 0) $samples[] = $exercise;
        }

        $verb = $dryRun ? 'à générer' : 'générés';
        $this->info(sprintf('%d exercices, %d énoncés %s, %d caractères%s.', $exercises->count(), $created, $verb, $spent, $left ? ", {$left} hors budget" : ''));
        if (! $dryRun) $this->line('Ce mois-ci : '.$voice->charactersThisMonth().' caractères générés au total.');
        foreach ($samples as $exercise) {
            foreach ($voice->segments($exercise) as $key => $text) {
                $hash = $voice->hash($voice->language($exercise), $text, $model);
                $clip = \App\Models\SpeechClip::query()->where('hash', $hash)->first();
                if ($clip) $this->line("  #{$exercise->id} {$key} : /storage/{$clip->file_path}  « ".mb_strimwidth($text, 0, 70, '…').' »');
            }
        }
        return self::SUCCESS;
    }
}

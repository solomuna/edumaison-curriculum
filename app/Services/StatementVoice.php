<?php

namespace App\Services;

use App\Models\Exercise;
use App\Models\SpeechClip;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Énoncés lus par Mama Judi (ElevenLabs) au lieu de la voix du téléphone.
 *
 * Un exercice est découpé en morceaux à lire (consigne, question, affirmation,
 * phrase à trous…). Chaque texte est généré une seule fois, rangé dans le
 * disque public (contenu pédagogique, aucune donnée d'enfant) et partagé par
 * toutes les familles. Tant qu'un morceau manque, l'application garde la voix
 * du téléphone.
 *
 * Dépense maîtrisée : la génération s'arrête au budget de la commande et au
 * plafond mensuel (ELEVENLABS_STATEMENT_MONTHLY_CHARS ; 0 = rien en
 * arrière-plan).
 */
class StatementVoice
{
    /** Mêmes matières « françaises » que l'application (ExercisePlayer). */
    private const FRENCH_SUBJECTS = ['french', 'francais', 'nlc', 'national languages and cultures'];

    /** Au-delà, le texte (un passage de lecture) reste à la voix du téléphone. */
    private const MAX_CHARS = 600;

    public function configured(): bool
    {
        return config('services.elevenlabs.key') !== '' && config('services.elevenlabs.voice_id') !== '';
    }

    public function model(): string
    {
        return (string) (config('services.elevenlabs.statement_model') ?: 'eleven_multilingual_v2');
    }

    public function language(Exercise $exercise): string
    {
        $subject = mb_strtolower((string) ($exercise->lesson?->unit?->integratedTheme?->subject?->name ?? ''));
        foreach (self::FRENCH_SUBJECTS as $french) {
            if (str_contains($subject, $french)) return 'fr';
        }
        return 'en';
    }

    /** Texte tel que Mama Judi le dit : un trou devient une courte pause. */
    public static function spoken(?string $text): string
    {
        $text = preg_replace('/_{2,}/u', '…', (string) $text);
        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    /**
     * Morceaux à lire, avec les clés qu'utilise l'application :
     * instructions, q:<i> (QCM), statement (vrai/faux), question (géométrie,
     * horloge), item:<i> (texte à trous), prompt (production écrite).
     *
     * @return array<string, string>
     */
    public function segments(Exercise $exercise): array
    {
        $content = is_array($exercise->content) ? $exercise->content : (json_decode((string) $exercise->content, true) ?: []);
        $segments = ['instructions' => $exercise->instructions];

        $questions = is_array($content['questions'] ?? null) ? $content['questions']
            : (isset($content['question'], $content['options']) ? [['question' => $content['question']]] : []);
        foreach (array_values($questions) as $i => $question) {
            if (is_array($question)) $segments["q:{$i}"] = $question['text'] ?? $question['question'] ?? '';
        }
        if (isset($content['statement'])) $segments['statement'] = $content['statement'];
        if ((isset($content['question']) && ! isset($content['options'])) || in_array($content['type'] ?? '', ['geometry', 'clock_reading'], true)) {
            $segments['question'] = $content['question'] ?? '';
        }
        $items = $content['items'] ?? $content['sentences'] ?? (isset($content['sentence']) ? [$content] : []);
        if (($content['type'] ?? '') === 'fill_in' || isset($content['sentence'])) {
            foreach (array_values((array) $items) as $i => $item) {
                if (is_array($item)) $segments["item:{$i}"] = $item['prompt'] ?? $item['text'] ?? $item['sentence'] ?? '';
            }
        }
        if (($content['type'] ?? '') === 'written_response') $segments['prompt'] = $content['prompt'] ?? '';

        $clean = [];
        foreach ($segments as $key => $text) {
            $text = self::spoken(is_string($text) ? $text : '');
            if ($text !== '' && mb_strlen($text) <= self::MAX_CHARS) $clean[$key] = $text;
        }
        return $clean;
    }

    public function hash(string $language, string $text, ?string $model = null): string
    {
        return hash('sha256', implode('|', [config('services.elevenlabs.voice_id'), $model ?? $this->model(), $language, $text]));
    }

    /**
     * Chaque morceau avec son texte : l'application vérifie qu'il correspond à
     * ce qu'elle affiche avant de le jouer (sinon, voix du téléphone).
     *
     * @return array{language: string, segments: array<string, array{text: string, url: string|null}>}
     */
    public function manifest(Exercise $exercise): array
    {
        $language = $this->language($exercise);
        $segments = $this->segments($exercise);
        $hashes = array_map(fn ($text) => $this->hash($language, $text), $segments);
        $clips = SpeechClip::query()->whereIn('hash', array_values($hashes))->pluck('file_path', 'hash');
        $out = [];
        foreach ($segments as $key => $text) {
            $out[$key] = ['text' => $text, 'url' => isset($clips[$hashes[$key]]) ? '/storage/'.$clips[$hashes[$key]] : null];
        }
        return ['language' => $language, 'segments' => $out];
    }

    /** Caractères générés depuis le début du mois (plafond mensuel). */
    public function charactersThisMonth(): int
    {
        return (int) SpeechClip::query()->where('created_at', '>=', now()->startOfMonth())->sum('characters');
    }

    /**
     * Génère les morceaux manquants d'un exercice, dans la limite de $budget
     * caractères. Renvoie [créés, caractères dépensés, morceaux manquants restants].
     *
     * @return array{0: int, 1: int, 2: int}
     */
    public function generateFor(Exercise $exercise, int $budget, ?string $model = null, bool $dryRun = false): array
    {
        $language = $this->language($exercise);
        $created = 0;
        $spent = 0;
        $left = 0;
        foreach ($this->segments($exercise) as $text) {
            $hash = $this->hash($language, $text, $model);
            if (SpeechClip::query()->where('hash', $hash)->exists()) continue;
            $chars = mb_strlen($text);
            if ($spent + $chars > $budget) { $left++; continue; }
            if (! $dryRun) $this->generate($text, $language, $model);
            $created++;
            $spent += $chars;
        }
        return [$created, $spent, $left];
    }

    public function generate(string $text, string $language, ?string $model = null): SpeechClip
    {
        if (! $this->configured()) {
            throw new RuntimeException('ElevenLabs is not configured (ELEVENLABS_API_KEY, ELEVENLABS_VOICE_ID).');
        }
        $model ??= $this->model();
        $hash = $this->hash($language, $text, $model);
        $payload = [
            'text' => $text,
            'model_id' => $model,
            'voice_settings' => ['stability' => 0.55, 'similarity_boost' => 0.75, 'style' => 0.2, 'use_speaker_boost' => true],
        ];
        // Les modèles « flash » / « turbo » acceptent la langue imposée (meilleure prononciation).
        if (str_contains($model, 'flash') || str_contains($model, 'turbo')) $payload['language_code'] = $language;

        $response = Http::timeout(40)
            ->withHeaders(['xi-api-key' => config('services.elevenlabs.key'), 'Accept' => 'audio/mpeg'])
            ->post('https://api.elevenlabs.io/v1/text-to-speech/'.rawurlencode(config('services.elevenlabs.voice_id')).'?output_format=mp3_44100_128', $payload);
        if (! $response->successful()) {
            throw new RuntimeException('ElevenLabs request failed with HTTP '.$response->status());
        }
        $path = "speech/{$hash}.mp3";
        Storage::disk('public')->put($path, $response->body());
        return SpeechClip::query()->updateOrCreate(['hash' => $hash], [
            'language' => $language, 'text' => $text, 'characters' => mb_strlen($text), 'model' => $model, 'file_path' => $path,
        ]);
    }
}

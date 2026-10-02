<?php

namespace App\Services;

use App\Models\Child;
use App\Models\ChildVoiceClip;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Répliques de Mama Judi avec le prénom de l'enfant, générées avec ElevenLabs
 * dans la même voix que le pack commun, seulement avec l'accord du foyer.
 *
 * - Fichiers sur le disque privé (jamais dans public/), servis après contrôle
 *   du foyer ; supprimés quand le parent retire son accord.
 * - Aucun prénom dans les journaux : on compte, on ne nomme pas.
 */
class ChildNameVoice
{
    /** Répliques par langue et par moment ; {name} = prénom de l'enfant. */
    public const LINES = [
        'en' => [
            'greeting' => ['Hello {name}! Are you ready to learn with me today?', 'Welcome back, {name}! Let\'s learn something new together.'],
            'correct' => ['Well done, {name}!', 'Yes, {name}! You got it!'],
            'session_perfect' => ['Perfect, {name}! I\'m so proud of you.'],
        ],
        'fr' => [
            'greeting' => ['Bonjour {name} ! On apprend ensemble aujourd\'hui ?', 'Te revoilà, {name} ! On va apprendre quelque chose de nouveau.'],
            'correct' => ['Bravo, {name} !', 'Oui, {name} ! Tu as trouvé !'],
            'session_perfect' => ['Parfait, {name} ! Je suis tellement fière de toi.'],
        ],
    ];

    public function configured(): bool
    {
        return config('services.elevenlabs.key') !== '' && config('services.elevenlabs.voice_id') !== '';
    }

    public function allowedFor(Child $child): bool
    {
        return (bool) $child->household?->child_name_voice_consent_at;
    }

    /**
     * Crée les répliques manquantes ou périmées (prénom changé). Sans accord du
     * foyer, supprime au contraire celles qui existent. Renvoie le nombre créé.
     */
    public function sync(Child $child): int
    {
        if (! $this->allowedFor($child)) {
            $this->purge($child);
            return 0;
        }
        if (! $this->configured()) {
            throw new RuntimeException('ElevenLabs is not configured (ELEVENLABS_API_KEY, ELEVENLABS_VOICE_ID).');
        }

        $name = trim(explode(' ', trim((string) $child->first_name))[0] ?? '');
        if ($name === '') return 0;

        $created = 0;
        foreach (self::LINES as $language => $events) {
            foreach ($events as $event => $templates) {
                foreach ($templates as $index => $template) {
                    $variant = $index + 1;
                    $text = str_replace('{name}', $name, $template);
                    $hash = hash('sha256', implode('|', [config('services.elevenlabs.voice_id'), config('services.elevenlabs.model'), $text]));
                    $clip = ChildVoiceClip::query()->where([
                        'child_id' => $child->id, 'language' => $language, 'event' => $event, 'variant' => $variant,
                    ])->first();
                    if ($clip && $clip->source_hash === $hash && Storage::disk('local')->exists($clip->file_path)) continue;

                    $path = sprintf('child-voice/%d/%d/%s/%s_%d.mp3', $child->household_id, $child->id, $language, $event, $variant);
                    Storage::disk('local')->put($path, $this->synthesize($text));
                    ChildVoiceClip::query()->updateOrCreate(
                        ['child_id' => $child->id, 'language' => $language, 'event' => $event, 'variant' => $variant],
                        ['file_path' => $path, 'source_hash' => $hash],
                    );
                    $created++;
                }
            }
        }
        if ($created > 0) Log::info('child-name-voice: clips generated', ['child_id' => $child->id, 'count' => $created]);
        return $created;
    }

    /** Supprime toutes les répliques personnelles d'un enfant (fichiers compris). */
    public function purge(Child $child): int
    {
        $clips = ChildVoiceClip::query()->where('child_id', $child->id)->get();
        foreach ($clips as $clip) Storage::disk('local')->delete($clip->file_path);
        ChildVoiceClip::query()->where('child_id', $child->id)->delete();
        return $clips->count();
    }

    private function synthesize(string $text): string
    {
        $response = Http::timeout(30)
            ->withHeaders(['xi-api-key' => config('services.elevenlabs.key'), 'Accept' => 'audio/mpeg'])
            ->post('https://api.elevenlabs.io/v1/text-to-speech/'.rawurlencode(config('services.elevenlabs.voice_id')).'?output_format=mp3_44100_128', [
                'text' => $text,
                'model_id' => config('services.elevenlabs.model'),
                'voice_settings' => ['stability' => 0.5, 'similarity_boost' => 0.75, 'style' => 0.3, 'use_speaker_boost' => true],
            ]);
        if (! $response->successful()) {
            // Le corps de la réponse peut citer le texte (donc le prénom) : on ne garde que le statut.
            throw new RuntimeException('ElevenLabs request failed with HTTP '.$response->status());
        }
        return $response->body();
    }
}

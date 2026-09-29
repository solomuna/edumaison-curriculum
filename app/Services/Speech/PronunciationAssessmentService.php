<?php

namespace App\Services\Speech;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class PronunciationAssessmentService
{
    public function available(): bool
    {
        return trim((string) config('services.azure_speech.key')) !== ''
            && trim((string) config('services.azure_speech.region')) !== '';
    }

    public function assess(string $audioDataUrl, string $referenceText, string $locale): array
    {
        if (! $this->available()) {
            throw new RuntimeException('The pronunciation assessment service is not configured.');
        }

        $referenceText = trim($referenceText);
        if ($referenceText === '') {
            throw ValidationException::withMessages(['reference_text' => 'A reference text is required.']);
        }

        if (! in_array($locale, ['en-GB', 'fr-FR'], true)) {
            throw ValidationException::withMessages(['locale' => 'This speaking language is not supported.']);
        }

        $audio = $this->decodeWaveDataUrl($audioDataUrl);
        $parameters = base64_encode(json_encode([
            'ReferenceText' => $referenceText,
            'GradingSystem' => 'HundredMark',
            'Granularity' => 'Word',
            'Dimension' => 'Comprehensive',
            'EnableMiscue' => true,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        $region = trim((string) config('services.azure_speech.region'));
        $endpoint = rtrim((string) config('services.azure_speech.endpoint'), '/');
        if ($endpoint === '') {
            $endpoint = "https://{$region}.stt.speech.microsoft.com";
        }
        $url = $endpoint.'/speech/recognition/conversation/cognitiveservices/v1?'.http_build_query([
            'language' => $locale,
            'format' => 'detailed',
        ]);

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Ocp-Apim-Subscription-Key' => (string) config('services.azure_speech.key'),
                'Pronunciation-Assessment' => $parameters,
            ])->timeout((int) config('services.azure_speech.timeout', 15))
                ->retry(1, 250, throw: false)
                ->withBody($audio, 'audio/wav; codecs=audio/pcm; samplerate=16000')
                ->post($url);
        } catch (ConnectionException $exception) {
            throw new RuntimeException('The pronunciation assessment service could not be reached.', 0, $exception);
        }

        if (! $response->successful()) {
            throw new RuntimeException('The pronunciation assessment service returned an error.');
        }

        return $this->normalizeResult($response->json());
    }

    public function normalizeResult(array $payload): array
    {
        if (($payload['RecognitionStatus'] ?? '') !== 'Success') {
            throw new RuntimeException('No usable speech was detected.');
        }

        $best = (array) ($payload['NBest'][0] ?? []);
        $assessment = (array) ($best['PronunciationAssessment'] ?? []);
        if ($assessment === []) {
            throw new RuntimeException('No pronunciation score was returned.');
        }

        $score = $this->score($assessment['PronScore'] ?? $assessment['AccuracyScore'] ?? null);
        $words = collect($best['Words'] ?? [])->map(function ($word) {
            $wordAssessment = (array) ($word['PronunciationAssessment'] ?? []);
            return [
                'word' => trim((string) ($word['Word'] ?? '')),
                'accuracy' => $this->score($wordAssessment['AccuracyScore'] ?? null),
                'error_type' => (string) ($wordAssessment['ErrorType'] ?? 'None'),
            ];
        })->filter(fn ($word) => $word['word'] !== '')->values()->all();

        return [
            'method' => 'azure_pronunciation_assessment',
            'transcript' => trim((string) ($payload['DisplayText'] ?? $best['Display'] ?? '')),
            'pronunciation_score' => $score,
            'accuracy_score' => $this->score($assessment['AccuracyScore'] ?? null),
            'fluency_score' => $this->nullableScore($assessment['FluencyScore'] ?? null),
            'completeness_score' => $this->nullableScore($assessment['CompletenessScore'] ?? null),
            'words' => $words,
        ];
    }

    private function decodeWaveDataUrl(string $dataUrl): string
    {
        if (! preg_match('#^data:audio/wav;base64,([A-Za-z0-9+/=]+)$#', trim($dataUrl), $matches)) {
            throw ValidationException::withMessages(['audio_data_url' => 'The speaking audio must be a WAV recording.']);
        }

        $audio = base64_decode($matches[1], true);
        if ($audio === false || strlen($audio) < 1_000 || strlen($audio) > 750_000) {
            throw ValidationException::withMessages(['audio_data_url' => 'The speaking audio size is invalid.']);
        }
        if (substr($audio, 0, 4) !== 'RIFF' || substr($audio, 8, 4) !== 'WAVE') {
            throw ValidationException::withMessages(['audio_data_url' => 'The speaking audio is not a valid WAV recording.']);
        }

        return $audio;
    }

    private function score(mixed $value): int
    {
        return max(0, min(100, (int) round((float) ($value ?? 0))));
    }

    private function nullableScore(mixed $value): ?int
    {
        return $value === null ? null : $this->score($value);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Exercise;
use App\Services\Speech\PronunciationAssessmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class SpeakingAssessmentController extends Controller
{
    public function store(
        Request $request,
        int $childId,
        Exercise $exercise,
        PronunciationAssessmentService $service,
    ) {
        $data = $request->validate([
            'item_index' => ['required', 'integer', 'min:0', 'max:100'],
            'audio_data_url' => ['required', 'string', 'max:1100000'],
        ]);

        $child = Child::query()->with('household')->findOrFail($childId);
        if (! $child->household?->speaking_analysis_consent_at) {
            throw ValidationException::withMessages([
                'audio_data_url' => 'Parent consent is required for automatic pronunciation analysis.',
            ]);
        }
        if (! $service->available()) {
            return response()->json(['message' => 'Automatic pronunciation analysis is not available.'], 503);
        }

        $content = is_array($exercise->content) ? $exercise->content : json_decode($exercise->content, true) ?? [];
        abort_unless(($content['type'] ?? '') === 'oral_drill', 404);
        $index = (int) $data['item_index'];
        $target = trim((string) ($content['items'][$index]['text'] ?? ''));
        if ($target === '') {
            throw ValidationException::withMessages(['item_index' => 'This speaking item does not exist.']);
        }

        $subject = strtolower((string) ($exercise->lesson?->unit?->integratedTheme?->subject?->name ?? ''));
        $contentLocale = (string) ($content['language'] ?? '');
        $locale = in_array($contentLocale, ['en-GB', 'fr-FR'], true)
            ? $contentLocale
            : (str_contains($subject, 'french') || str_contains($subject, 'franc') ? 'fr-FR' : 'en-GB');

        try {
            $assessment = $service->assess((string) $data['audio_data_url'], $target, $locale);
        } catch (RuntimeException $exception) {
            report($exception);
            return response()->json(['message' => 'No reliable pronunciation result was produced. Please try again.'], 422);
        }

        $token = (string) Str::uuid();
        Cache::put("speaking-assessment:{$token}", [
            'child_id' => $childId,
            'exercise_id' => $exercise->id,
            'item_index' => $index,
            'target_hash' => hash('sha256', $target),
            'assessment' => $assessment,
        ], now()->addMinutes(15));

        return response()->json([
            'assessment_token' => $token,
            'assessment' => $assessment,
        ])->header('Cache-Control', 'private, no-store, max-age=0');
    }
}

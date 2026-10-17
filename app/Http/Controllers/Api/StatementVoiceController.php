<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateStatementVoice;
use App\Models\Exercise;
use App\Services\StatementVoice;

/** Énoncés d'un exercice lus par Mama Judi : adresse de chaque morceau prêt, null sinon. */
class StatementVoiceController extends Controller
{
    public function show(int $exerciseId, StatementVoice $voice)
    {
        $exercise = Exercise::query()->with('lesson.unit.integratedTheme.subject')->where('is_active', true)->findOrFail($exerciseId);
        $manifest = $voice->manifest($exercise);
        // Morceaux manquants : préparés en arrière-plan si le plafond mensuel le permet.
        if (in_array(null, array_column($manifest['segments'], 'url'), true)
            && $voice->configured()
            && (int) config('services.elevenlabs.statement_monthly_chars') > $voice->charactersThisMonth()) {
            GenerateStatementVoice::dispatch($exercise->id);
        }
        return response()->json($manifest)->header('Cache-Control', 'private, max-age=300');
    }
}

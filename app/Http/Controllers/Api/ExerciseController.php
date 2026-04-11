<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseAttempt;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function forChild(Request $request, int $childId)
    {
        $levelId = (int) $request->query('level_id', 0);

        $query = Exercise::with(['lesson.unit.integratedTheme.subject'])
            ->where('is_active', true);

        if ($levelId) {
            // Révision générale : tous les niveaux <= level_id du child
            $query->whereHas('lesson.unit.integratedTheme.subject', function ($q) use ($levelId) {
                $q->where('level_id', '<=', $levelId);
            });
        }

        $exercises = $query
            ->inRandomOrder()
            ->limit(100)
            ->get()
            ->map(fn($e) => [
                'id'           => $e->id,
                'title'        => $e->title,
                'instructions' => $e->instructions,
                'category'     => $e->category,
                'difficulty'   => $e->difficulty,
                'subject'      => $e->lesson?->unit?->integratedTheme?->subject?->name ?? 'General',
                'level_id'     => $e->lesson?->unit?->integratedTheme?->subject?->level_id,
                'content'      => is_array($e->content) ? $e->content : json_decode($e->content, true),
            ]);

        return response()->json($exercises);
    }

    public function forSubject(Request $request, int $childId, int $subjectId)
    {
        $exercises = Exercise::with(['lesson.unit.integratedTheme.subject'])
            ->where('is_active', true)
            ->whereHas('lesson.unit.integratedTheme', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->get()
            ->map(fn($e) => [
                'id'           => $e->id,
                'title'        => $e->title,
                'instructions' => $e->instructions,
                'category'     => $e->category,
                'difficulty'   => $e->difficulty,
                'subject'      => $e->lesson?->unit?->integratedTheme?->subject?->name ?? 'General',
                'level_id'     => $e->lesson?->unit?->integratedTheme?->subject?->level_id,
                'content'      => is_array($e->content) ? $e->content : json_decode($e->content, true),
            ]);

        return response()->json($exercises);
    }

    public function attempt(Request $request)
    {
        $request->validate([
            'child_id'    => 'required|integer',
            'exercise_id' => 'required|integer',
            'score'       => 'nullable|integer',
            'status'      => 'nullable|string',
        ]);

        $schoolYear = SchoolYear::where('is_current', true)->first();

        ExerciseAttempt::create([
            'child_id'       => $request->child_id,
            'exercise_id'    => $request->exercise_id,
            'school_year_id' => $schoolYear?->id ?? 1,
            'score'          => $request->score ?? 0,
            'max_score'      => 100,
            'status'         => $request->status ?? 'completed',
            'attempted_at'   => now(),
        ]);

        return response()->json(['success' => true]);
    }
}

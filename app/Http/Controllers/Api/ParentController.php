<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ExerciseAttempt;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\FamilyContext;

class ParentController extends Controller
{
    // Vue globale de tous les enfants
    public function dashboard(Request $request)
    {
        $schoolYear = SchoolYear::where("is_current", true)->first();

        $householdId = FamilyContext::householdId($request);
        abort_unless($householdId, 401);
        $children = Child::with("level")->where('household_id', $householdId)
            ->where("is_active", true)
            ->get()
            ->map(function ($child) use ($schoolYear) {
                return [
                    "id"         => $child->id,
                    "name"       => $child->first_name . " " . $child->last_name,
                    "level"      => $child->level?->name ?? "N/A",
                    "level_id"   => $child->level_id,
                    ...$this->progressSummary($child, $schoolYear),
                ];
            });

        return response()->json([
            "school_year" => $schoolYear?->label ?? "2025-2026",
            "children"    => $children,
            "total_completed_exercises" => $children->sum("completed_exercises"),
            "total_attempts"  => $children->sum("attempts"),
        ]);
    }

    // Détail d un enfant
    public function childDetail(Request $request, int $childId)
    {
        $schoolYear = SchoolYear::where("is_current", true)->first();
        $householdId = FamilyContext::householdId($request);
        abort_unless($householdId, 401);
        $child = Child::with("level")->where('household_id', $householdId)
            ->findOrFail($childId);

        $recentAttempts = ExerciseAttempt::with("exercise")
            ->where("child_id", $childId)
            ->where("school_year_id", $schoolYear?->id)
            ->orderByDesc("attempted_at")
            ->limit(10)
            ->get()
            ->map(fn($a) => [
                "exercise"   => $a->exercise?->title ?? "Exercice",
                "score"      => $a->score,
                "status"     => $a->status,
                "date"       => $a->attempted_at,
            ]);

        return response()->json([
            "child"          => [
                "id"    => $child->id,
                "name"  => $child->first_name . " " . $child->last_name,
                "level" => $child->level?->name,
            ],
            "summary"        => $this->progressSummary($child, $schoolYear),
            "recent_attempts" => $recentAttempts,
        ]);
    }

    private function progressSummary(Child $child, ?SchoolYear $schoolYear): array
    {
        if (!$schoolYear || !$child->level_id) {
            return [
                "attempts" => 0,
                "completed_attempts" => 0,
                "completed_exercises" => 0,
                "total_exercises" => 0,
                "avg_score" => 0,
                "progress_pct" => 0,
            ];
        }

        $attempts = ExerciseAttempt::query()
            ->where("child_id", $child->id)
            ->where("school_year_id", $schoolYear->id)
            ->count();

        $completedAttempts = ExerciseAttempt::query()
            ->where("child_id", $child->id)
            ->where("school_year_id", $schoolYear->id)
            ->where("status", "completed")
            ->count();

        $levelExercises = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->join('subjects', 'integrated_themes.subject_id', '=', 'subjects.id')
            ->where('subjects.level_id', $child->level_id)
            ->where('subjects.is_active', true)
            ->where('exercises.is_active', true)
            ->whereNull('exercises.deleted_at');

        $totalExercises = (clone $levelExercises)
            ->distinct()
            ->count('exercises.id');

        $verifiedStatuses = ['auto_checked', 'client_checked', 'parent_verified'];
        $verifiedAttempts = DB::table('exercise_attempts')
            ->joinSub(
                (clone $levelExercises)->select('exercises.id'),
                'level_exercises',
                'exercise_attempts.exercise_id',
                '=',
                'level_exercises.id'
            )
            ->where('exercise_attempts.child_id', $child->id)
            ->where('exercise_attempts.school_year_id', $schoolYear->id)
            ->where('exercise_attempts.status', 'completed')
            ->whereIn('exercise_attempts.verification_status', $verifiedStatuses);

        $completedExercises = (clone $verifiedAttempts)
            ->distinct()
            ->count('exercise_attempts.exercise_id');

        $latestAttemptIds = (clone $verifiedAttempts)
            ->whereNotNull('exercise_attempts.score')
            ->selectRaw('MAX(exercise_attempts.id) AS id')
            ->groupBy('exercise_attempts.exercise_id');

        $avgScore = DB::table('exercise_attempts')
            ->whereIn('id', $latestAttemptIds)
            ->avg('score') ?? 0;

        return [
            "attempts" => $attempts,
            "completed_attempts" => $completedAttempts,
            "completed_exercises" => $completedExercises,
            "total_exercises" => $totalExercises,
            "avg_score" => round($avgScore),
            "progress_pct" => $totalExercises > 0
                ? min(100, round(($completedExercises / $totalExercises) * 100))
                : 0,
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ExerciseAttempt;
use App\Models\SchoolYear;
use App\Models\Household;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    // Vue globale de tous les enfants
    public function dashboard()
    {
        $schoolYear = SchoolYear::where("is_current", true)->first();

        $children = Child::with("level")
            ->where("is_active", true)
            ->get()
            ->map(function ($child) use ($schoolYear) {
                $attempts = ExerciseAttempt::where("child_id", $child->id)
                    ->where("school_year_id", $schoolYear?->id)
                    ->count();

                $completed = ExerciseAttempt::where("child_id", $child->id)
                    ->where("school_year_id", $schoolYear?->id)
                    ->where("status", "completed")
                    ->count();

                $avgScore = ExerciseAttempt::where("child_id", $child->id)
                    ->where("school_year_id", $schoolYear?->id)
                    ->avg("score") ?? 0;

                return [
                    "id"         => $child->id,
                    "name"       => $child->first_name . " " . $child->last_name,
                    "level"      => $child->level?->name ?? "N/A",
                    "level_id"   => $child->level_id,
                    "attempts"   => $attempts,
                    "completed"  => $completed,
                    "avg_score"  => round($avgScore),
                    "pct"        => $attempts > 0 ? round(($completed / $attempts) * 100) : 0,
                ];
            });

        return response()->json([
            "school_year" => $schoolYear?->label ?? "2025-2026",
            "children"    => $children,
            "total_completed" => $children->sum("completed"),
            "total_attempts"  => $children->sum("attempts"),
        ]);
    }

    // Détail d un enfant
    public function childDetail(int $childId)
    {
        $schoolYear = SchoolYear::where("is_current", true)->first();
        $child = Child::with("level")->findOrFail($childId);

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
            "recent_attempts" => $recentAttempts,
        ]);
    }
}
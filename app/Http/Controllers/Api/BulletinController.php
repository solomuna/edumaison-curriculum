<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class BulletinController extends Controller
{
    public function forChild(int $childId)
    {
        $child = DB::table('children')->where('id', $childId)->first();
        if (!$child) return response()->json(null);

        $level  = DB::table('levels')->where('id', $child->level_id)->first();
        $year   = DB::table('school_years')->orderByDesc('id')->first();

        $results = DB::table('school_results')
            ->join('subjects','school_results.subject_id','=','subjects.id')
            ->where('school_results.child_id', $childId)
            ->select(
                'subjects.id as subject_id',
                'subjects.name as subject',
                'school_results.average_score',
                'school_results.max_score',
                'school_results.appreciation',
                'school_results.teacher_comment'
            )
            ->orderBy('subjects.id')
            ->get();

        if ($results->isEmpty()) return response()->json(null);

        $avg     = round($results->avg('average_score'), 2);
        $total_xp = DB::table('exercise_attempts')
            ->where('child_id', $childId)->sum('score');

        // ── Class rank & average ──────────────────────────────────────────────
        $classmateIds = DB::table('children')
            ->where('level_id', $child->level_id)
            ->pluck('id')
            ->toArray();

        $classAverages = [];
        foreach ($classmateIds as $cid) {
            $cAvg = DB::table('school_results')
                ->where('child_id', $cid)
                ->avg('average_score');
            if ($cAvg !== null) {
                $classAverages[$cid] = round((float)$cAvg, 2);
            }
        }

        arsort($classAverages);
        $rank      = array_search($childId, array_keys($classAverages));
        $rank      = ($rank !== false) ? $rank + 1 : null;
        $classSize = count($classAverages);
        $classAvg  = $classSize > 0
            ? round(array_sum($classAverages) / $classSize, 2)
            : null;

        // ── Student number ────────────────────────────────────────────────────
        $studentNumber = 'EDM-' . str_pad($child->level_id, 2, '0', STR_PAD_LEFT)
                       . '-' . str_pad($childId, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'child' => [
                'name'           => $child->first_name . ' ' . $child->last_name,
                'first_name'     => $child->first_name,
                'level'          => $level->name ?? '',
                'birth_date'     => $child->birth_date,
                'student_number' => $studentNumber,
            ],
            'year'         => $year->name ?? date('Y'),
            'results'      => $results,
            'average'      => $avg,
            'total_xp'     => (int)$total_xp,
            'rank'         => $rank,
            'class_size'   => $classSize,
            'class_average'=> $classAvg,
        ]);
    }
}

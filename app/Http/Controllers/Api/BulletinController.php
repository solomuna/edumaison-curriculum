<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class BulletinController extends Controller
{
    private function appreciation(float $avg): string {
        if ($avg >= 16) return "Excellent";
        if ($avg >= 14) return "Very Good";
        if ($avg >= 12) return "Good";
        if ($avg >= 10) return "Keep It Up!";
        if ($avg >= 8)  return "More Effort Needed";
        return "Let's Work Harder!";
    }

    public function forChild(int $childId)
    {
        $child = DB::table('children')->where('id', $childId)->first();
        if (!$child) return response()->json(null);

        $level  = DB::table('levels')->where('id', $child->level_id)->first();
        $year   = DB::table('school_years')->orderByDesc('id')->first();

        // ?? Calcul dynamique depuis exercise_attempts ?????????????????????
        $dynamic = DB::select("
            SELECT
                s.id   AS subject_id,
                s.name AS subject,
                ROUND(AVG(ea.score::float / NULLIF(ea.max_score,0) * 20)::numeric, 2) AS average_score,
                20.0  AS max_score,
                sr.teacher_comment
            FROM exercise_attempts ea
            JOIN exercises e   ON ea.exercise_id  = e.id
            JOIN lessons l     ON e.lesson_id     = l.id
            JOIN units u       ON l.unit_id       = u.id
            JOIN integrated_themes it ON u.integrated_theme_id = it.id
            JOIN subjects s    ON it.subject_id   = s.id
            LEFT JOIN school_results sr ON sr.child_id = :cid AND sr.subject_id = s.id
            WHERE ea.child_id = :cid2
              AND ea.status   = 'completed'
              AND ea.max_score > 0
            GROUP BY s.id, s.name, sr.teacher_comment
            ORDER BY s.id
        ", ['cid' => $childId, 'cid2' => $childId]);

        // ?? Fallback school_results si pas encore d'attempts ?????????????
        if (empty($dynamic)) {
            $manual = DB::table('school_results')
                ->join('subjects','school_results.subject_id','=','subjects.id')
                ->where('school_results.child_id', $childId)
                ->select(
                    'subjects.id as subject_id',
                    'subjects.name as subject',
                    'school_results.average_score',
                    'school_results.max_score',
                    'school_results.teacher_comment'
                )
                ->orderBy('subjects.id')
                ->get();

            if ($manual->isEmpty()) return response()->json(null);

            $results = $manual->map(function($r) {
                $avg = (float)$r->average_score;
                return (object)[
                    'subject_id'     => $r->subject_id,
                    'subject'        => $r->subject,
                    'average_score'  => $avg,
                    'max_score'      => 20,
                    'appreciation'   => $this->appreciation($avg),
                    'teacher_comment'=> $r->teacher_comment,
                    'total_score'    => $avg,
                    'source'         => 'manual',
                ];
            });
        } else {
            $results = collect($dynamic)->map(function($r) {
                $avg = (float)$r->average_score;
                return (object)[
                    'subject_id'     => $r->subject_id,
                    'subject'        => $r->subject,
                    'average_score'  => $avg,
                    'max_score'      => 20,
                    'appreciation'   => $this->appreciation($avg),
                    'teacher_comment'=> $r->teacher_comment ?? null,
                    'total_score'    => $avg,
                    'source'         => 'dynamic',
                ];
            });
        }

        $avg      = round($results->avg('average_score'), 2);
        $total_xp = DB::table('exercise_attempts')->where('child_id', $childId)->sum('score');

        // ?? Rang & moyenne classe ?????????????????????????????????????????
        $classmateIds = DB::table('children')->where('level_id', $child->level_id)->pluck('id')->toArray();
        $classAverages = [];
        foreach ($classmateIds as $cid) {
            $cAvg = DB::table('school_results')->where('child_id', $cid)->avg('average_score');
            if ($cAvg !== null) $classAverages[$cid] = round((float)$cAvg, 2);
        }
        arsort($classAverages);
        $rank      = array_search($childId, array_keys($classAverages));
        $rank      = ($rank !== false) ? $rank + 1 : null;
        $classSize = count($classAverages);
        $classAvg  = $classSize > 0 ? round(array_sum($classAverages) / $classSize, 2) : null;

        $studentNumber = 'EDM-' . str_pad($child->level_id, 2, '0', STR_PAD_LEFT)
                       . '-' . str_pad($childId, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'child' => [
                'name'           => $child->first_name . ' ' . $child->last_name,
                'first_name'     => $child->first_name,
                'level'          => $level->name ?? '',
                'level_id'       => $child->level_id,
                'birth_date'     => $child->birth_date,
                'student_number' => $studentNumber,
            ],
            'year'         => $year->label ?? $year->name ?? date('Y'),
            'results'      => $results->values(),
            'average'      => $avg,
            'total_xp'     => (int)$total_xp,
            'rank'         => $rank,
            'class_size'   => $classSize,
            'class_average'=> $classAvg,
            'source'       => empty($dynamic) ? 'manual' : 'dynamic',
        ]);
    }
}

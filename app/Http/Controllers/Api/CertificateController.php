<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function forChild(int $childId)
    {
        $child = DB::table('children')->where('id', $childId)->first();
        if (!$child) return response()->json([]);

        $levelName = DB::table('levels')->where('id', $child->level_id)->value('name') ?? '';

        // Get all subjects for this level
        $subjects = DB::table('subjects')
            ->where('level_id', $child->level_id)
            ->where('is_active', true)
            ->get();

        $certificates = [];
        foreach ($subjects as $sub) {
            // Total exercises for this subject
            $total = DB::table('exercises')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->join('units','lessons.unit_id','=','units.id')
                ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
                ->where('integrated_themes.subject_id', $sub->id)
                ->count();

            if ($total === 0) continue;

            // Completed by this child
            $done = DB::table('exercise_attempts')
                ->join('exercises','exercise_attempts.exercise_id','=','exercises.id')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->join('units','lessons.unit_id','=','units.id')
                ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
                ->where('exercise_attempts.child_id', $childId)
                ->where('integrated_themes.subject_id', $sub->id)
                ->distinct('exercise_attempts.exercise_id')
                ->count('exercise_attempts.exercise_id');

            $pct = round($done / $total * 100);
            $earned = $pct >= 80;

            if ($earned) {
                $certificates[] = [
                    'subject_id'   => $sub->id,
                    'subject_name' => $sub->name,
                    'child_name'   => $child->first_name,
                    'level'        => $levelName,
                    'pct'          => $pct,
                    'done'         => $done,
                    'total'        => $total,
                    'earned'       => true,
                    'earned_at'    => DB::table('exercise_attempts')
                        ->join('exercises','exercise_attempts.exercise_id','=','exercises.id')
                        ->join('lessons','exercises.lesson_id','=','lessons.id')
                        ->join('units','lessons.unit_id','=','units.id')
                        ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
                        ->where('exercise_attempts.child_id', $childId)
                        ->where('integrated_themes.subject_id', $sub->id)
                        ->max('exercise_attempts.attempted_at'),
                ];
            }
        }

        return response()->json($certificates);
    }
}

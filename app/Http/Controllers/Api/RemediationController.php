<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class RemediationController extends Controller
{
    public function forChild(int $childId)
    {
        $child = DB::table('children')->where('id', $childId)->first();
        if (!$child) return response()->json([]);

        // Calcul dynamique depuis exercise_attempts
        $dynamicRaw = DB::select("
            SELECT
                s.id   AS subject_id,
                s.name AS subject,
                ROUND(AVG(ea.score::float / NULLIF(ea.max_score,0) * 20)::numeric, 2) AS average_score
            FROM exercise_attempts ea
            JOIN exercises e   ON ea.exercise_id  = e.id
            JOIN lessons l     ON e.lesson_id     = l.id
            JOIN units u       ON l.unit_id       = u.id
            JOIN integrated_themes it ON u.integrated_theme_id = it.id
            JOIN subjects s    ON it.subject_id   = s.id
            WHERE ea.child_id = :cid
              AND ea.status   = 'completed'
              AND ea.max_score > 0
            GROUP BY s.id, s.name
            HAVING ROUND(AVG(ea.score::float / NULLIF(ea.max_score,0) * 20)::numeric, 2) < 12
            ORDER BY average_score ASC
        ", ['cid' => $childId]);

        // Fallback sur school_results si pas assez d'attempts
        if (empty($dynamicRaw)) {
            $weakSubjects = DB::table('school_results')
                ->join('subjects','school_results.subject_id','=','subjects.id')
                ->where('school_results.child_id', $childId)
                ->where('school_results.average_score', '<', 12)
                ->orderBy('school_results.average_score')
                ->select('subjects.id as subject_id','subjects.name as subject','school_results.average_score')
                ->get();
        } else {
            $weakSubjects = collect($dynamicRaw);
        }

        if ($weakSubjects->isEmpty()) {
            return response()->json(['status' => 'excellent', 'plans' => []]);
        }

        $plans = [];
        foreach ($weakSubjects as $sub) {
            $avg = (float)$sub->average_score;
            $priority = $avg < 7 ? 'critical' : ($avg < 10 ? 'high' : 'medium');

            // Get exercises for this subject that child hasn't done yet
            $done = DB::table('exercise_attempts')
                ->join('exercises','exercise_attempts.exercise_id','=','exercises.id')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->join('units','lessons.unit_id','=','units.id')
                ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
                ->where('exercise_attempts.child_id', $childId)
                ->where('integrated_themes.subject_id', $sub->subject_id)
                ->pluck('exercise_attempts.exercise_id')
                ->toArray();

            $todo = DB::table('exercises')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->join('units','lessons.unit_id','=','units.id')
                ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
                ->where('integrated_themes.subject_id', $sub->subject_id)
                ->whereNotIn('exercises.id', $done)
                ->inRandomOrder()
                ->limit($priority === 'critical' ? 15 : ($priority === 'high' ? 10 : 7))
                ->select('exercises.id','exercises.title','exercises.category','exercises.content')
                ->get();

            $totalExercises = DB::table('exercises')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->join('units','lessons.unit_id','=','units.id')
                ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
                ->where('integrated_themes.subject_id', $sub->subject_id)
                ->count();

            $doneCount = count($done);

            $plans[] = [
                'subject_id'    => $sub->subject_id,
                'subject'       => $sub->subject,
                'average'       => $avg,
                                'priority'      => $priority,
                'target'        => $avg < 7 ? 10 : ($avg < 10 ? 12 : 14),
                'exercises'     => $todo,
                'done_count'    => $doneCount,
                'total_count'   => $totalExercises,
                'pct_done'      => $totalExercises > 0 ? round($doneCount / $totalExercises * 100) : 0,
                'tips'          => $this->getTips($sub->subject, $avg),
            ];
        }

        return response()->json([
            'status'     => 'needs_work',
            'child_name' => $child->first_name,
            'plans'      => $plans,
        ]);
    }

    private function getTips(string $subject, float $avg): array
    {
        $tips = [
            'Mathematics' => [
                'Practise counting and number recognition every day.',
                'Use real objects (coins, fruits) to understand addition and subtraction.',
                'Work on times tables — 5 minutes daily makes a big difference.',
                'Try the measurement exercises — cm, kg, litres.',
            ],
            'Science and Technology' => [
                'Read about animals and plants around your home.',
                'Observe nature — what grows in your garden?',
                'Try the body parts and senses exercises.',
                'Ask questions about how things work.',
            ],
            'English' => [
                'Read a short passage every day out loud.',
                'Learn 5 new vocabulary words each week.',
                'Practise writing simple sentences.',
                'Listen and repeat — use the oral drill exercises.',
            ],
            'French' => [
                'Apprends 5 mots de vocabulaire par jour.',
                'Lis un texte court chaque soir.',
                'Pratique la conjugaison des verbes être et avoir.',
                'Écris des phrases simples chaque jour.',
            ],
            'ICT' => [
                'Learn the names of computer parts.',
                'Practise keyboard shortcuts.',
                'Try the internet safety exercises.',
            ],
            'Citizenship' => [
                'Learn the colours of the Cameroon flag.',
                'Study the 10 regions of Cameroon.',
                'Review rights and duties exercises.',
            ],
        ];

        $subjectTips = $tips[$subject] ?? ['Keep practising every day!', 'Ask for help when stuck.'];
        if ($avg < 7) {
            array_unshift($subjectTips, '⚠️ This subject needs urgent attention — practise daily!');
        }
        return array_slice($subjectTips, 0, 3);
    }
}

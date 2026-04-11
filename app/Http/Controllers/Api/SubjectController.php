<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $levelId = $request->query("level_id");
        $subjects = Subject::where("is_active", true)
            ->when($levelId, fn($q) => $q->where("level_id", $levelId))
            ->orderBy("order")
            ->get()
            ->map(fn($s) => [
                "id"    => $s->id,
                "name"  => $s->name,
                "color" => $s->color,
                "icon"  => $s->icon,
            ])
            ->groupBy("name")
            ->map(fn($g) => $g->first())
            ->values();
        return response()->json($subjects);
    }

    public function units(int $subjectId, int $childId)
    {
        $units = DB::table('units')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id', $subjectId)
            ->select('units.id','units.name','units.summary')
            ->get();

        $result = $units->map(function($u) use ($childId, $subjectId) {
            $total = DB::table('exercises')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->where('lessons.unit_id', $u->id)
                ->count();

            $done = DB::table('exercise_attempts')
                ->join('exercises','exercise_attempts.exercise_id','=','exercises.id')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->where('exercise_attempts.child_id', $childId)
                ->where('lessons.unit_id', $u->id)
                ->distinct('exercise_attempts.exercise_id')
                ->count('exercise_attempts.exercise_id');

            return [
                'id'    => $u->id,
                'name'  => $u->name,
                'total' => $total,
                'done'  => $done,
                'pct'     => $total > 0 ? round($done / $total * 100) : 0,
                'summary' => $u->summary ?? null,
            ];
        })->filter(fn($u) => $u['total'] > 0)->values();

        return response()->json($result);
    }

    public function exercisesByUnit(int $unitId, int $childId)
    {
        $exercises = DB::table('exercises')
            ->join('lessons','exercises.lesson_id','=','lessons.id')
            ->where('lessons.unit_id', $unitId)
            ->select('exercises.id','exercises.title','exercises.category','exercises.content')
            ->inRandomOrder()
            ->get();

        return response()->json($exercises);
    }
}

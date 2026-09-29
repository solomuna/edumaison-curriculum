<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Services\NationalLanguageProfileService;
use App\Support\FamilyContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $levelId = $request->query("level_id");
        $childId = (int) $request->query('child_id', 0);
        $languageProfile = null;
        if ($childId > 0) {
            $householdId = (int) (FamilyContext::householdId($request) ?? 0);
            abort_unless($householdId > 0, 403);
            $languageProfile = app(NationalLanguageProfileService::class)
                ->forChild($childId, $householdId);
        }
        $subjects = Subject::where("is_active", true)
            ->when($levelId, fn($q) => $q->where("level_id", $levelId))
            ->orderBy("order")
            ->get()
            ->map(fn($s) => [
                "id"    => $s->id,
                "name"  => $s->name,
                "color" => $s->color,
                "icon"  => $s->icon,
                "national_language_profile" => $s->name === 'National Languages and Cultures'
                    ? $languageProfile
                    : null,
            ])
            ->groupBy("name")
            ->map(fn($g) => $g->first())
            ->values();
        return response()->json($subjects);
    }

    public function units(int $subjectId, int $childId)
    {
        $householdId = (int) (FamilyContext::householdId(request()) ?? 0);
        abort_unless($householdId > 0, 403);
        $profiles = app(NationalLanguageProfileService::class);
        $schoolYearId = DB::table('school_years')->where('is_current', true)->value('id');
        $units = DB::table('units')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id', $subjectId)
            ->select('units.id','units.name','units.summary')
            ->get();

        $result = $units->map(function($u) use ($childId, $subjectId, $schoolYearId, $householdId, $profiles) {
            $totalQuery = DB::table('exercises')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->where('lessons.unit_id', $u->id)
                ->where('exercises.is_active', true)
                ->whereNull('exercises.deleted_at');
            $profiles->restrictExerciseQuery($totalQuery, $childId, $householdId);
            $total = $totalQuery->count();

            $doneQuery = DB::table('exercise_attempts')
                ->join('exercises','exercise_attempts.exercise_id','=','exercises.id')
                ->join('lessons','exercises.lesson_id','=','lessons.id')
                ->where('exercise_attempts.child_id', $childId)
                ->when($schoolYearId, fn($query) => $query->where('exercise_attempts.school_year_id', $schoolYearId))
                ->where('lessons.unit_id', $u->id)
                ->where('exercises.is_active', true)
                ->whereNull('exercises.deleted_at')
                ->where('exercise_attempts.status', 'completed')
                ->whereIn('exercise_attempts.verification_status', ['auto_checked', 'client_checked', 'parent_verified']);
            $profiles->restrictExerciseQuery($doneQuery, $childId, $householdId);
            $done = $doneQuery->distinct('exercise_attempts.exercise_id')->count('exercise_attempts.exercise_id');

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
        $householdId = (int) (FamilyContext::householdId(request()) ?? 0);
        abort_unless($householdId > 0, 403);
        $schoolYearId = DB::table('school_years')->where('is_current', true)->value('id');
        $exerciseQuery = DB::table('exercises')
            ->join('lessons','exercises.lesson_id','=','lessons.id')
            ->where('lessons.unit_id', $unitId)
            ->where('exercises.is_active', true)
            ->whereNull('exercises.deleted_at')
            ->select('exercises.id','exercises.title','exercises.category','exercises.content');
        app(NationalLanguageProfileService::class)
            ->restrictExerciseQuery($exerciseQuery, $childId, $householdId);
        $exercises = $exerciseQuery
            ->inRandomOrder()
            ->get();

        $latestAttempts = DB::table('exercise_attempts')
            ->where('child_id', $childId)
            ->when($schoolYearId, fn($query) => $query->where('school_year_id', $schoolYearId))
            ->whereIn('exercise_id', $exercises->pluck('id'))
            ->orderByDesc('attempted_at')
            ->orderByDesc('id')
            ->get(['exercise_id', 'status', 'verification_status'])
            ->unique('exercise_id')
            ->keyBy('exercise_id');

        $exercises->transform(function ($exercise) use ($latestAttempts) {
            $attempt = $latestAttempts->get($exercise->id);
            $exercise->attempt_status = $attempt?->status;
            $exercise->verification_status = $attempt?->verification_status;
            return $exercise;
        });

        return response()->json($exercises);
    }
}

<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    // Get upcoming/active exams for a child
    public function forChild(int $childId)
    {
        $householdId = DB::table('children')->where('id', $childId)->value('household_id');
        if (!$householdId) return response()->json([]);

        $exams = DB::table('exams')
            ->join('subjects','exams.subject_id','=','subjects.id')
            ->where('exams.household_id', $householdId)
            ->whereIn('exams.status', ['scheduled','active'])
            ->select('exams.*', 'subjects.name as subject_name')
            ->orderBy('exams.scheduled_at')
            ->get();

        return response()->json($exams->map(function($e) use ($childId) {
            $result = DB::table('exam_results')
                ->where('exam_id', $e->id)
                ->where('child_id', $childId)
                ->first();
            return array_merge((array)$e, [
                'already_taken' => !is_null($result),
                'score' => $result?->score,
                'total' => $result?->total,
            ]);
        }));
    }

    // Get exam questions
    public function questions(int $examId, int $childId)
    {
        $exam = DB::table('exams')->where('id', $examId)->first();
        if (!$exam) return response()->json(null, 404);

        // Get random exercises for this subject
        $exercises = DB::table('exercises')
            ->join('lessons','exercises.lesson_id','=','lessons.id')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id', $exam->subject_id)
            ->whereIn('exercises.category', ['mathematics','science','reading','ict','revision'])
            ->inRandomOrder()
            ->limit($exam->question_count)
            ->select('exercises.id','exercises.title','exercises.category','exercises.content')
            ->get();

        // Mark exam as active if scheduled_at has passed
        if ($exam->status === 'scheduled' && now() >= \Carbon\Carbon::parse($exam->scheduled_at)) {
            DB::table('exams')->where('id', $examId)->update(['status' => 'active']);
        }

        return response()->json([
            'exam'      => $exam,
            'exercises' => $exercises,
        ]);
    }

    // Submit exam result
    public function submit(Request $request, int $examId)
    {
        $data = $request->validate([
            'child_id'         => 'required|integer',
            'score'            => 'required|integer',
            'total'            => 'required|integer',
            'duration_seconds' => 'nullable|integer',
            'started_at'       => 'nullable|string',
        ]);

        // Prevent duplicate submissions
        $existing = DB::table('exam_results')
            ->where('exam_id', $examId)
            ->where('child_id', $data['child_id'])
            ->exists();

        if ($existing) return response()->json(['error' => 'Already submitted'], 422);

        DB::table('exam_results')->insert([
            'exam_id'          => $examId,
            'child_id'         => $data['child_id'],
            'score'            => $data['score'],
            'total'            => $data['total'],
            'duration_seconds' => $data['duration_seconds'] ?? null,
            'started_at'       => $data['started_at'] ?? null,
            'finished_at'      => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // Get all results for this exam
        $results = DB::table('exam_results')
            ->join('children','exam_results.child_id','=','children.id')
            ->where('exam_results.exam_id', $examId)
            ->select('children.first_name','exam_results.score','exam_results.total','exam_results.duration_seconds')
            ->orderByDesc('exam_results.score')
            ->get();

        return response()->json(['success' => true, 'leaderboard' => $results]);
    }

    // Create exam (from parent)
    public function create(Request $request)
    {
        $data = $request->validate([
            'household_id'     => 'required|integer',
            'subject_id'       => 'required|integer',
            'title'            => 'required|string',
            'question_count'   => 'integer|min:5|max:30',
            'duration_minutes' => 'integer|min:5|max:120',
            'scheduled_at'     => 'required|date',
        ]);

        $id = DB::table('exams')->insertGetId(array_merge($data, [
            'status'     => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return response()->json(['id' => $id, 'success' => true]);
    }

    // Get results for parent
    public function results(int $examId)
    {
        $exam = DB::table('exams')
            ->join('subjects','exams.subject_id','=','subjects.id')
            ->where('exams.id', $examId)
            ->select('exams.*','subjects.name as subject_name')
            ->first();

        $results = DB::table('exam_results')
            ->join('children','exam_results.child_id','=','children.id')
            ->where('exam_results.exam_id', $examId)
            ->select('children.first_name','exam_results.score','exam_results.total','exam_results.duration_seconds','exam_results.finished_at')
            ->orderByDesc('exam_results.score')
            ->get()
            ->map(function($r, $i) {
                return array_merge((array)$r, [
                    'rank' => $i + 1,
                    'pct'  => $r->total > 0 ? round($r->score / $r->total * 100) : 0,
                ]);
            });

        return response()->json(['exam' => $exam, 'results' => $results]);
    }
}

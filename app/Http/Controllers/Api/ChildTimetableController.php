<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\FamilyContext;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

class ChildTimetableController extends Controller
{
    public function index(Request $request, int $childId): JsonResponse
    {
        $householdId = $this->householdId($request);
        $this->ownedChild($householdId, $childId);
        return response()->json(DB::table('child_timetable_entries')
            ->where('household_id', $householdId)->where('child_id', $childId)
            ->orderBy('weekday')->orderBy('start_time')->get());
    }

    public function store(Request $request, int $childId): JsonResponse
    {
        $householdId = $this->householdId($request);
        $this->ownedChild($householdId, $childId);
        $data = $request->validate([
            'weekday' => ['required', 'integer', 'between:1,7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'subject_name' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);
        $overlap = DB::table('child_timetable_entries')->where('child_id', $childId)
            ->where('weekday', $data['weekday'])
            ->where('start_time', '<', $data['end_time'])->where('end_time', '>', $data['start_time'])->exists();
        abort_if($overlap, 422, 'Cet horaire chevauche déjà un autre cours.');
        $id = DB::table('child_timetable_entries')->insertGetId(array_merge($data, [
            'household_id' => $householdId, 'child_id' => $childId, 'created_at' => now(), 'updated_at' => now(),
        ]));
        return response()->json(DB::table('child_timetable_entries')->find($id), 201);
    }

    public function revisionSuggestions(Request $request, int $childId): JsonResponse
    {
        $householdId = $this->householdId($request);
        $this->ownedChild($householdId, $childId);
        $data = $request->validate(['date' => ['nullable', 'date_format:Y-m-d']]);
        $date = isset($data['date'])
            ? CarbonImmutable::createFromFormat('Y-m-d', $data['date'])
            : CarbonImmutable::today(config('app.timezone'));
        $child = DB::table('children')->where('id', $childId)->first(['level_id']);
        $subjects = DB::table('subjects')->where('level_id', $child->level_id)
            ->where('is_active', true)->orderBy('order')->get(['id', 'name']);

        $build = function (CarbonImmutable $day) use ($householdId, $childId, $subjects) {
            return DB::table('child_timetable_entries')
                ->where('household_id', $householdId)->where('child_id', $childId)
                ->where('weekday', $day->isoWeekday())->orderBy('start_time')->get()
                ->map(function ($entry) use ($subjects) {
                    $needle = Str::lower(Str::ascii(trim($entry->subject_name)));
                    $match = $subjects->first(function ($subject) use ($needle) {
                        $candidate = Str::lower(Str::ascii($subject->name));
                        return $candidate === $needle || str_contains($candidate, $needle) || str_contains($needle, $candidate);
                    });
                    return [
                        'timetable_id' => $entry->id,
                        'subject_name' => $entry->subject_name,
                        'subject_id' => $match?->id,
                        'matched_name' => $match?->name,
                        'start_time' => substr($entry->start_time, 0, 5),
                        'end_time' => substr($entry->end_time, 0, 5),
                    ];
                })->values();
        };

        $today = $build($date);
        $tomorrow = $build($date->addDay());
        $next = collect();
        $nextDate = null;
        if ($today->isEmpty() && $tomorrow->isEmpty()) {
            for ($offset = 2; $offset <= 7; $offset++) {
                $candidate = $date->addDays($offset);
                $rows = $build($candidate);
                if ($rows->isNotEmpty()) { $next = $rows; $nextDate = $candidate->toDateString(); break; }
            }
        }

        return response()->json([
            'child_id' => $childId,
            'date' => $date->toDateString(),
            'today' => $today,
            'tomorrow' => $tomorrow,
            'next' => $next,
            'next_date' => $nextDate,
        ]);
    }

    public function destroy(Request $request, int $childId, int $entryId): JsonResponse
    {
        $householdId = $this->householdId($request);
        $this->ownedChild($householdId, $childId);
        $deleted = DB::table('child_timetable_entries')->where('id', $entryId)
            ->where('household_id', $householdId)->where('child_id', $childId)->delete();
        abort_unless($deleted === 1, 404);
        return response()->json(['ok' => true]);
    }

    private function householdId(Request $request): int
    {
        $id = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($id > 0, 401, 'Accès familial requis.');
        return $id;
    }

    private function ownedChild(int $householdId, int $childId): void
    {
        abort_unless(DB::table('children')->where('id', $childId)->where('household_id', $householdId)->where('is_active', true)->exists(), 404);
    }
}

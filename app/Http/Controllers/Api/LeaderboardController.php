<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function household(int $childId)
    {
        // Get household_id for this child
        $householdId = DB::table('children')->where('id', $childId)->value('household_id');
        if (!$householdId) return response()->json([]);

        // Get all children in same household
        $children = DB::table('children')
            ->where('household_id', $householdId)
            ->where('is_active', true)
            ->get(['id', 'first_name', 'last_name', 'level_id']);

        $results = [];
        foreach ($children as $c) {
            $totalXP = DB::table('exercise_attempts')
                ->where('child_id', $c->id)->sum('score') ?? 0;

            $totalDone = DB::table('exercise_attempts')
                ->where('child_id', $c->id)->count();

            $todayDone = DB::table('exercise_attempts')
                ->where('child_id', $c->id)
                ->whereDate('attempted_at', now()->toDateString())
                ->count();

            // Streak
            $days = DB::table('exercise_attempts')
                ->where('child_id', $c->id)
                ->selectRaw('DATE(attempted_at) as day')
                ->groupBy('day')
                ->orderByDesc('day')
                ->pluck('day')
                ->toArray();

            $streak = 0;
            $today = now()->toDateString();
            $yesterday = now()->subDay()->toDateString();
            if (!empty($days) && ($days[0] === $today || $days[0] === $yesterday)) {
                $expected = \Carbon\Carbon::parse($days[0]);
                foreach ($days as $day) {
                    if (\Carbon\Carbon::parse($day)->toDateString() === $expected->toDateString()) {
                        $streak++;
                        $expected->subDay();
                    } else break;
                }
            }

            $levelName = DB::table('levels')->where('id', $c->level_id)->value('name') ?? '';

            $results[] = [
                'id'         => $c->id,
                'name'       => $c->first_name,
                'level'      => $levelName,
                'xp'         => (int)$totalXP,
                'total_done' => (int)$totalDone,
                'today_done' => (int)$todayDone,
                'streak'     => $streak,
                'is_current' => $c->id === $childId,
            ];
        }

        // Sort by XP desc
        usort($results, fn($a, $b) => $b['xp'] - $a['xp']);
        foreach ($results as $i => &$r) $r['rank'] = $i + 1;

        return response()->json($results);
    }
}

<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class StreakController extends Controller
{
    public function forChild(int $childId)
    {
        // Get all distinct days with attempts, ordered desc
        $days = DB::table('exercise_attempts')
            ->where('child_id', $childId)
            ->selectRaw('DATE(attempted_at) as day')
            ->groupBy('day')
            ->orderByDesc('day')
            ->pluck('day')
            ->toArray();

        if (empty($days)) {
            return response()->json(['streak' => 0, 'best_streak' => 0, 'total_days' => 0]);
        }

        // Current streak — count consecutive days from today or yesterday
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $streak = 0;

        // Start counting only if latest day is today or yesterday
        if ($days[0] === $today || $days[0] === $yesterday) {
            $expected = \Carbon\Carbon::parse($days[0]);
            foreach ($days as $day) {
                if (\Carbon\Carbon::parse($day)->toDateString() === $expected->toDateString()) {
                    $streak++;
                    $expected->subDay();
                } else {
                    break;
                }
            }
        }

        // Best streak
        $best = 0;
        $current = 1;
        for ($i = 1; $i < count($days); $i++) {
            $prev = \Carbon\Carbon::parse($days[$i - 1]);
            $curr = \Carbon\Carbon::parse($days[$i]);
            if ($prev->diffInDays($curr) === 1) {
                $current++;
                $best = max($best, $current);
            } else {
                $current = 1;
            }
        }
        $best = max($best, $streak);

        // Total attempts today
        $todayCount = DB::table('exercise_attempts')
            ->where('child_id', $childId)
            ->whereDate('attempted_at', $today)
            ->count();

        // Total XP (score)
        $totalXP = DB::table('exercise_attempts')
            ->where('child_id', $childId)
            ->sum('score');

        return response()->json([
            'streak'      => $streak,
            'best_streak' => $best,
            'total_days'  => count($days),
            'today_count' => $todayCount,
            'total_xp'    => $totalXP,
        ]);
    }
}

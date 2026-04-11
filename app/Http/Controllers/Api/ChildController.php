<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChildController extends Controller
{
    public function uploadAvatar(Request $request, int $childId)
    {
        $child = DB::table('children')->where('id', $childId)->first();
        if (!$child) return response()->json(['error' => 'Child not found'], 404);

        $request->validate(['avatar' => 'required|image|max:2048']);

        $path = $request->file('avatar')->store('avatars', 'public');

        DB::table('children')->where('id', $childId)->update([
            'avatar'     => $path,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success'    => true,
            'avatar_url' => asset('storage/' . $path),
        ]);
    }

    // Level progression map
    private array $nextLevel = [
        2 => 3,   // Pre-Nursery -> Nursery 1
        3 => 4,   // Nursery 1   -> Nursery 2
        4 => 5,   // Nursery 2   -> Class 1
        5 => 6,   // Class 1     -> Class 2
        6 => 7,   // Class 2     -> Class 3
        7 => 8,   // Class 3     -> Class 4
        8 => 9,   // Class 4     -> Class 5
        9 => 10,  // Class 5     -> Class 6
    ];

    public function promote(int $childId)
    {
        $child = DB::table('children')->where('id', $childId)->first();
        if (!$child) return response()->json(['error' => 'Child not found'], 404);

        $nextLevelId = $this->nextLevel[$child->level_id] ?? null;
        if (!$nextLevelId) {
            return response()->json(['error' => 'No next level (Class 6 is the highest)'], 422);
        }

        $nextLevel = DB::table('levels')->where('id', $nextLevelId)->first();

        DB::table('children')->where('id', $childId)->update([
            'level_id'   => $nextLevelId,
            'updated_at' => now(),
        ]);

        // Archive exercise attempts (mark as archived)
        // We keep attempts but just update the child's level
        // Optionally reset streaks for new year
        DB::table('streaks')->where('child_id', $childId)->update([
            'streak'      => 0,
            'best_streak' => DB::raw('streak'),
            'updated_at'  => now(),
        ]);

        return response()->json([
            'success'    => true,
            'child_id'   => $childId,
            'new_level'  => $nextLevel->name ?? '',
            'new_level_id' => $nextLevelId,
        ]);
    }

    public function promoteAll()
    {
        $children = DB::table('children')->get();
        $promoted = [];
        foreach ($children as $child) {
            $nextLevelId = $this->nextLevel[$child->level_id] ?? null;
            if ($nextLevelId) {
                DB::table('children')->where('id', $child->id)->update([
                    'level_id' => $nextLevelId, 'updated_at' => now()
                ]);
                DB::table('streaks')->where('child_id', $child->id)->update([
                    'streak' => 0, 'best_streak' => DB::raw('streak'), 'updated_at' => now()
                ]);
                $promoted[] = $child->id;
            }
        }
        return response()->json(['success' => true, 'promoted' => $promoted]);
    }
}

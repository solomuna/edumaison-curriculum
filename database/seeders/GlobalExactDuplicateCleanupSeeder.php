<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GlobalExactDuplicateCleanupSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $groups = Exercise::query()
                ->where('is_active', true)
                ->withCount('attempts')
                ->orderBy('id')
                ->get()
                ->groupBy(fn (Exercise $exercise): string => $exercise->lesson_id.'|'.hash(
                    'sha256',
                    json_encode($exercise->content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                ))
                ->filter(fn (Collection $group): bool => $group->count() > 1);

            $deactivated = 0;

            foreach ($groups as $group) {
                $canonical = $group
                    ->sort(function (Exercise $left, Exercise $right): int {
                        return [$right->attempts_count, $right->created_at?->getTimestamp() ?? 0, $right->id]
                            <=> [$left->attempts_count, $left->created_at?->getTimestamp() ?? 0, $left->id];
                    })
                    ->first();

                $duplicateIds = $group->pluck('id')->reject(fn (int $id): bool => $id === $canonical->id);

                $deactivated += Exercise::query()
                    ->whereIn('id', $duplicateIds)
                    ->where('is_active', true)
                    ->update(['is_active' => false, 'updated_at' => now()]);
            }

            $this->command?->info("Exact duplicate cleanup: {$groups->count()} groups, {$deactivated} rows deactivated.");
        });
    }
}

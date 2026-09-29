<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class1FamilySpeakingPlacementSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $exercise = DB::table('exercises')->where('id', 10)->lockForUpdate()->first();
            $targetLesson = DB::table('lessons')->where('id', 8)->first();

            if (! $exercise
                || $exercise->title !== 'Family Words - Listen and Repeat'
                || (json_decode($exercise->content, true)['type'] ?? null) !== 'oral_drill') {
                throw new \RuntimeException('The Class 1 family speaking exercise has changed unexpectedly.');
            }

            if (! $targetLesson
                || $targetLesson->unit_id !== 3
                || $targetLesson->name !== 'Lesson 1 - Mother and Father') {
                throw new \RuntimeException('The Class 1 My Family target lesson has changed unexpectedly.');
            }

            if ($exercise->lesson_id === 8) {
                return;
            }

            if ($exercise->lesson_id !== 374) {
                throw new \RuntimeException('The family speaking exercise is no longer in its expected source lesson.');
            }

            DB::table('exercises')->where('id', 10)->update([
                'lesson_id' => 8,
                'updated_at' => now(),
            ]);
        });
    }
}

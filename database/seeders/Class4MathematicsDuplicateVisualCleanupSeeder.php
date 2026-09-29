<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4MathematicsDuplicateVisualCleanupSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $subjectId = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 4')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'Mathematics')
                ->value('subjects.id');

            if (! $subjectId) {
                throw new \RuntimeException('Class 4 Mathematics subject not found.');
            }

            $mathExerciseIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->whereNull('exercises.deleted_at')
                ->pluck('exercises.id');

            // The same twelve seed exercises were imported three times.
            // Preserve every row and attempt, but expose only the latest series.
            $olderDuplicateIds = array_merge(range(185, 196), range(268, 279));
            DB::table('exercises')
                ->whereIn('id', $olderDuplicateIds)
                ->whereIn('id', $mathExerciseIds)
                ->update(['is_active' => false, 'updated_at' => now()]);

            DB::table('exercises')
                ->whereIn('id', range(351, 362))
                ->whereIn('id', $mathExerciseIds)
                ->update(['is_active' => true, 'updated_at' => now()]);

            $activeRows = DB::table('exercises')
                ->whereIn('id', $mathExerciseIds)
                ->where('is_active', true)
                ->select('id', 'content')
                ->get();

            foreach ($activeRows as $row) {
                $content = json_decode($row->content, true);
                if (! is_array($content)) {
                    continue;
                }

                $changed = false;
                foreach (['illustration', 'image_url'] as $decorativeKey) {
                    if (array_key_exists($decorativeKey, $content)) {
                        unset($content[$decorativeKey]);
                        $changed = true;
                    }
                }

                if ($changed) {
                    DB::table('exercises')->where('id', $row->id)->update([
                        'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }
}

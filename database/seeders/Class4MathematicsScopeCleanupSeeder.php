<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4MathematicsScopeCleanupSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $outOfScopeIds = [
                97, 98, 99,
                353, 1290, 1291, 1292, 1293, 1294,
                357,
                358, 1080, 1081, 1082, 1083, 1084, 1085,
                1249, 1250, 1251, 1252, 1254, 1255,
                1274, 1275,
            ];

            $class4MathIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->join('subjects', 'integrated_themes.subject_id', '=', 'subjects.id')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 4')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'Mathematics')
                ->whereIn('exercises.id', $outOfScopeIds)
                ->pluck('exercises.id');

            if ($class4MathIds->count() !== count($outOfScopeIds)) {
                throw new \RuntimeException('The complete Class 4 Mathematics scope-cleanup set was not found.');
            }

            DB::table('exercises')->whereIn('id', $class4MathIds)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
        });
    }
}

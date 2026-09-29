<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4MathematicsOfficialStructureSeeder extends Seeder
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

            $themes = [
                'sets-and-logic' => ['Sets and Logic', 1],
                'numbers-and-operations' => ['Numbers and Operations', 2],
                'measurement-and-size' => ['Measurement and Size', 3],
                'geometry-and-space' => ['Geometry and Space', 4],
                'statistics-and-graphs' => ['Statistics and Graphs', 5],
            ];

            $themeIds = [];
            foreach ($themes as $slug => [$name, $order]) {
                $id = DB::table('integrated_themes')->where([
                    'subject_id' => $subjectId,
                    'slug' => $slug,
                ])->value('id');

                $values = [
                    'name' => $name,
                    'description' => 'Official Class 4 Mathematics component from the MINEDUB Level II curriculum (2018).',
                    'order' => $order,
                    'is_active' => true,
                    'updated_at' => now(),
                ];

                if ($id) {
                    DB::table('integrated_themes')->where('id', $id)->update($values);
                } else {
                    $id = DB::table('integrated_themes')->insertGetId($values + [
                        'subject_id' => $subjectId,
                        'slug' => $slug,
                        'created_at' => now(),
                    ]);
                }
                $themeIds[$slug] = $id;
            }

            // Reuse the existing curriculum units while placing them under the correct official component.
            DB::table('units')->where('id', 279)->update(['integrated_theme_id' => $themeIds['numbers-and-operations'], 'name' => 'Numbers and Operations', 'updated_at' => now()]);
            DB::table('units')->where('id', 280)->update(['integrated_theme_id' => $themeIds['numbers-and-operations'], 'name' => 'Fractions and Decimals', 'updated_at' => now()]);
            DB::table('units')->where('id', 281)->update(['integrated_theme_id' => $themeIds['geometry-and-space'], 'name' => 'Geometry and Space', 'updated_at' => now()]);
            DB::table('units')->where('id', 282)->update(['integrated_theme_id' => $themeIds['measurement-and-size'], 'name' => 'Measurement and Size', 'updated_at' => now()]);
            DB::table('units')->where('id', 418)->update(['integrated_theme_id' => $themeIds['sets-and-logic'], 'updated_at' => now()]);

            $statisticsUnitId = $this->upsertUnit($themeIds['statistics-and-graphs'], 'statistics-and-graphs-class-4', 'Statistics and Graphs', 1);
            $statisticsLessonId = $this->upsertLesson($statisticsUnitId, 'statistics-and-graphs', 'Statistics and Graphs', 1);

            $moneyUnitId = $this->upsertUnit($themeIds['numbers-and-operations'], 'money-calculations-class-4', 'Money Calculations', 3);
            $moneyLessonId = $this->upsertLesson($moneyUnitId, 'profit-loss-and-prices', 'Profit, Loss and Prices', 1);

            $areaUnitId = $this->upsertUnit($themeIds['measurement-and-size'], 'perimeter-and-area-class-4', 'Perimeter and Area', 2);
            $areaLessonId = $this->upsertLesson($areaUnitId, 'perimeter-and-area', 'Perimeter and Area', 1);

            $setsLessonId = DB::table('lessons')->where('slug', 'intersection-and-union-of-sets')->where('unit_id', 418)->value('id');
            if (! $setsLessonId) {
                throw new \RuntimeException('Sets and Logic lesson not found.');
            }

            $this->move([409, 410, 1055, 1056, 1057, 1058, 356], $setsLessonId);
            $this->move([418, 419, 420, 1818, 1819, 1820, 1821, 1822, 1823, 1824, 1825, 1826, 351, 360], 329);
            $this->move([1097], 330);
            $this->move([630, 631], 331);
            $this->move([362, 916, 917, 918, 950, 951, 952, 953, 954, 955, 956, 957, 958, 1006, 1007, 1008, 1009, 1010], 332);
            $this->move([96, 354, 1011, 1012, 1013, 1014, 1015, 1016, 1017, 1018], $areaLessonId);
            $this->move([1083, 1084, 358, 1080, 1081, 1082, 1085], $moneyLessonId);
            $this->move([1249, 1250, 1251, 1252, 1253, 1254, 1255], $statisticsLessonId);

            DB::table('lessons')->whereIn('id', [53, 333])->update(['is_active' => false, 'updated_at' => now()]);
            DB::table('units')->whereIn('id', [35, 283])->update(['is_active' => false, 'updated_at' => now()]);
            DB::table('integrated_themes')
                ->where('subject_id', $subjectId)
                ->whereIn('slug', ['fractions', 'large-numbers'])
                ->update(['is_active' => false, 'updated_at' => now()]);

            DB::table('lessons')->where('id', 329)->update(['name' => 'Numbers and Operations', 'updated_at' => now()]);
            DB::table('lessons')->where('id', 330)->update(['name' => 'Fractions and Decimals', 'updated_at' => now()]);
            DB::table('lessons')->where('id', 331)->update(['name' => 'Geometry and Space', 'updated_at' => now()]);
            DB::table('lessons')->where('id', 332)->update(['name' => 'Measurement and Size', 'updated_at' => now()]);
        });
    }

    private function upsertUnit(int $themeId, string $slug, string $name, int $order): int
    {
        $id = DB::table('units')->where(['integrated_theme_id' => $themeId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => $name.' for Class 4.',
            'summary' => $name.' aligned to the official MINEDUB Level II curriculum.',
            'order' => $order,
            'estimated_weeks' => 2,
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($id) {
            DB::table('units')->where('id', $id)->update($values);
            return $id;
        }
        return DB::table('units')->insertGetId($values + ['integrated_theme_id' => $themeId, 'slug' => $slug, 'created_at' => now()]);
    }

    private function upsertLesson(int $unitId, string $slug, string $name, int $order): int
    {
        $id = DB::table('lessons')->where(['unit_id' => $unitId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => $name.' activities for Class 4.',
            'content' => $name.' practice aligned to the official curriculum.',
            'order' => $order,
            'estimated_minutes' => 30,
            'type' => 'mathematics',
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($id) {
            DB::table('lessons')->where('id', $id)->update($values);
            return $id;
        }
        return DB::table('lessons')->insertGetId($values + ['unit_id' => $unitId, 'slug' => $slug, 'created_at' => now()]);
    }

    private function move(array $exerciseIds, int $lessonId): void
    {
        DB::table('exercises')->whereIn('id', $exerciseIds)->update(['lesson_id' => $lessonId, 'updated_at' => now()]);
    }
}

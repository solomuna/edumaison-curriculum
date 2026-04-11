<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ── School Years ──────────────────────────────────────
        $schoolYears = json_decode(file_get_contents(database_path('data/school_years.json')), true);
        foreach ($schoolYears as $sy) {
            DB::table('school_years')->updateOrInsert(
                ['label' => $sy['label']],
                array_merge($sy, ['created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('✅ School years seedés');

        // ── Households ────────────────────────────────────────
        $households = json_decode(file_get_contents(database_path('data/households.json')), true);
        foreach ($households as $h) {
            DB::table('households')->updateOrInsert(
                ['name' => $h['name']],
                array_merge($h, ['created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('✅ Households seedés');

        // ── Subjects ──────────────────────────────────────────
        $subjects = json_decode(file_get_contents(database_path('data/subjects.json')), true);
        foreach ($subjects as $s) {
            $level = DB::table('levels')->where('slug', $s['level_slug'])->first();
            if (!$level) continue;
            DB::table('subjects')->updateOrInsert(
                ['slug' => $s['slug']],
                [
                    'level_id'   => $level->id,
                    'name'       => $s['name'],
                    'slug'       => $s['slug'],
                    'color'      => $s['color'] ?? null,
                    'order'      => $s['order'] ?? 0,
                    'is_active'  => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
        $this->command->info('✅ Subjects seedés');
    }
}

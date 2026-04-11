<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThemesUnitsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ── Integrated Themes ─────────────────────────────────
        $themes = json_decode(file_get_contents(database_path('data/integrated_themes.json')), true);
        foreach ($themes as $t) {
            $subject = DB::table('subjects')->where('slug', $t['subject_slug'])->first();
            if (!$subject) continue;
            DB::table('integrated_themes')->updateOrInsert(
                ['slug' => $t['slug']],
                [
                    'subject_id'  => $subject->id,
                    'name'        => $t['name'],
                    'slug'        => $t['slug'],
                    'order'       => $t['order'] ?? 0,
                    'is_active'   => true,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]
            );
        }
        $this->command->info('✅ Integrated Themes seedés');

        // ── Units ─────────────────────────────────────────────
        $units = json_decode(file_get_contents(database_path('data/units.json')), true);
        foreach ($units as $u) {
            $theme = DB::table('integrated_themes')->where('slug', $u['theme_slug'])->first();
            if (!$theme) continue;
            DB::table('units')->updateOrInsert(
                ['slug' => $u['slug']],
                [
                    'integrated_theme_id' => $theme->id,
                    'name'               => $u['name'],
                    'slug'               => $u['slug'],
                    'order'              => $u['order'] ?? 0,
                    'estimated_weeks'    => $u['weeks'] ?? null,
                    'is_active'          => true,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]
            );
        }
        $this->command->info('✅ Units seedés');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LessonsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $lessons = json_decode(file_get_contents(database_path('data/lessons.json')), true);
        $count = 0;

        foreach ($lessons as $l) {
            $unit = DB::table('units')->where('slug', $l['unit_slug'])->first();
            if (!$unit) continue;

            DB::table('lessons')->updateOrInsert(
                ['slug' => $l['slug']],
                [
                    'unit_id'            => $unit->id,
                    'name'               => $l['name'],
                    'slug'               => $l['slug'],
                    'order'              => $l['order'] ?? 0,
                    'type'               => $l['type'] ?? 'mixed',
                    'estimated_minutes'  => $l['minutes'] ?? null,
                    'is_active'          => true,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]
            );
            $count++;
        }

        $this->command->info("✅ {$count} Lessons seedées");
    }
}

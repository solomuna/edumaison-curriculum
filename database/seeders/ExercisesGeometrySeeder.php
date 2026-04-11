<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExercisesGeometrySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $exercises = json_decode(file_get_contents(database_path("data/exercises_geometry.json")), true);
        $count = 0;

        foreach ($exercises as $e) {
            $lesson = DB::table("lessons")->where("slug", $e["lesson_slug"])->first();
            if (!$lesson) {
                $this->command->warn("Lesson not found: " . $e["lesson_slug"]);
                continue;
            }

            DB::table("exercises")->updateOrInsert(
                ["title" => $e["title"], "lesson_id" => $lesson->id],
                [
                    "lesson_id"         => $lesson->id,
                    "title"             => $e["title"],
                    "instructions"      => $e["instructions"],
                    "category"          => $e["category"],
                    "difficulty"        => $e["difficulty"],
                    "estimated_minutes" => $e["minutes"] ?? null,
                    "content"           => json_encode($e["content"]),
                    "is_active"         => true,
                    "created_at"        => $now,
                    "updated_at"        => $now,
                ]
            );
            $count++;
        }

        $this->command->info("Exercices geometrie seedes: {$count}");
    }
}
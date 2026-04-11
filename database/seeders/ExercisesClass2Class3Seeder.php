<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExercisesClass2Class3Seeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $count = 0;

        $files = [
            database_path("data/exercises_class2.json"),
            database_path("data/exercises_class3.json"),
        ];

        foreach ($files as $file) {
            $exercises = json_decode(file_get_contents($file), true);
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
        }

        $this->command->info("Exercices Class 2 + Class 3 seedes: {$count}");
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MissingClass4Class5Seeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Units manquantes
        $units = [
            ["theme_slug" => "cameroon-eng-c4",     "name" => "Unit 1 - Our Country",       "slug" => "our-country-c4",       "order" => 1, "weeks" => 2],
            ["theme_slug" => "science-nature-eng-c4","name" => "Unit 1 - Plants and Animals", "slug" => "plants-animals-c4",   "order" => 1, "weeks" => 2],
            ["theme_slug" => "health-eng-c4",       "name" => "Unit 1 - Staying Healthy",   "slug" => "staying-healthy-c4",   "order" => 1, "weeks" => 2],
            ["theme_slug" => "fractions-c4",        "name" => "Unit 1 - Fractions",         "slug" => "fractions-c4",         "order" => 1, "weeks" => 2],
            ["theme_slug" => "geometry-c4",         "name" => "Unit 1 - Geometry",          "slug" => "geometry-c4",          "order" => 1, "weeks" => 2],
            ["theme_slug" => "large-numbers-c4",    "name" => "Unit 1 - Large Numbers",     "slug" => "large-numbers-c4",     "order" => 1, "weeks" => 2],
            ["theme_slug" => "africa-eng-c5",       "name" => "Unit 1 - Africa Overview",   "slug" => "africa-overview-c5",   "order" => 1, "weeks" => 2],
            ["theme_slug" => "technology-eng-c5",   "name" => "Unit 1 - Modern Technology", "slug" => "modern-tech-c5",       "order" => 1, "weeks" => 2],
            ["theme_slug" => "culture-eng-c5",      "name" => "Unit 1 - Our Culture",       "slug" => "our-culture-c5",       "order" => 1, "weeks" => 2],
            ["theme_slug" => "decimals-c5",         "name" => "Unit 1 - Decimals",          "slug" => "decimals-c5",          "order" => 1, "weeks" => 2],
            ["theme_slug" => "percentages-c5",      "name" => "Unit 1 - Percentages",       "slug" => "percentages-c5",       "order" => 1, "weeks" => 2],
            ["theme_slug" => "problem-solving-c5",  "name" => "Unit 1 - Problem Solving",   "slug" => "problem-solving-c5",   "order" => 1, "weeks" => 2],
        ];

        foreach ($units as $u) {
            $theme = DB::table("integrated_themes")->where("slug", $u["theme_slug"])->first();
            if (!$theme) { $this->command->warn("Theme not found: " . $u["theme_slug"]); continue; }
            DB::table("units")->updateOrInsert(
                ["slug" => $u["slug"]],
                ["integrated_theme_id" => $theme->id, "name" => $u["name"], "slug" => $u["slug"],
                 "order" => $u["order"], "estimated_weeks" => $u["weeks"], "is_active" => true,
                 "created_at" => $now, "updated_at" => $now]
            );
        }
        $this->command->info("Units Class 4+5 seedees !");

        // Lecons manquantes
        $lessons = [
            ["unit_slug" => "our-country-c4",     "name" => "Lesson 1 - Our Country",       "slug" => "our-country-c4",       "type" => "reading",     "order" => 1, "minutes" => 35],
            ["unit_slug" => "plants-animals-c4",  "name" => "Lesson 1 - Plants and Animals","slug" => "plants-animals-c4",    "type" => "science",     "order" => 1, "minutes" => 35],
            ["unit_slug" => "staying-healthy-c4", "name" => "Lesson 1 - Staying Healthy",   "slug" => "staying-healthy-c4",   "type" => "reading",     "order" => 1, "minutes" => 30],
            ["unit_slug" => "fractions-c4",       "name" => "Lesson 1 - Fractions",         "slug" => "fractions-c4",         "type" => "mathematics", "order" => 1, "minutes" => 35],
            ["unit_slug" => "africa-overview-c5", "name" => "Lesson 1 - Africa Overview",   "slug" => "africa-overview-c5",   "type" => "reading",     "order" => 1, "minutes" => 35],
            ["unit_slug" => "modern-tech-c5",     "name" => "Lesson 1 - Modern Technology", "slug" => "modern-tech-c5",       "type" => "reading",     "order" => 1, "minutes" => 30],
            ["unit_slug" => "our-culture-c5",     "name" => "Lesson 1 - Our Culture",       "slug" => "our-culture-c5",       "type" => "reading",     "order" => 1, "minutes" => 30],
            ["unit_slug" => "decimals-c5",        "name" => "Lesson 1 - Decimals",          "slug" => "decimals-c5",          "type" => "mathematics", "order" => 1, "minutes" => 35],
            ["unit_slug" => "percentages-c5",     "name" => "Lesson 1 - Percentages",       "slug" => "percentages-c5",       "type" => "mathematics", "order" => 1, "minutes" => 35],
        ];

        foreach ($lessons as $l) {
            $unit = DB::table("units")->where("slug", $l["unit_slug"])->first();
            if (!$unit) { $this->command->warn("Unit not found: " . $l["unit_slug"]); continue; }
            DB::table("lessons")->updateOrInsert(
                ["slug" => $l["slug"]],
                ["unit_id" => $unit->id, "name" => $l["name"], "slug" => $l["slug"],
                 "order" => $l["order"], "type" => $l["type"], "estimated_minutes" => $l["minutes"],
                 "is_active" => true, "created_at" => $now, "updated_at" => $now]
            );
        }
        $this->command->info("Lecons Class 4+5 seedees !");
    }
}
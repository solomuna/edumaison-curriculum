<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\SchoolCompetency;
use Illuminate\Database\Seeder;

class Class4EnglishExerciseCompetencyPilotSeeder extends Seeder
{
    public function run(): void
    {
        $oral = SchoolCompetency::query()
            ->where('name', 'Listening and speaking in familiar contexts')
            ->whereHas('subject.level', fn ($query) => $query->where('name', 'Class 4'))
            ->firstOrFail();

        $exerciseIds = Exercise::query()
            ->whereIn('id', [49, 51, 53])
            ->where('category', 'oral_drill')
            ->where('is_active', true)
            ->pluck('id');

        abort_unless($exerciseIds->count() === 3, 409, 'Expected three active oral drills.');
        $oral->exercises()->syncWithoutDetaching($exerciseIds);
    }
}

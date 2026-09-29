<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\ExerciseMediaAsset;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4ExerciseVisualCleanupSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $english = Subject::query()
                ->where('name', 'English')
                ->whereHas('level', fn ($query) => $query->where('name', 'Class 4'))
                ->firstOrFail();

            Exercise::query()
                ->where('is_active', true)
                ->whereHas('lesson.unit.integratedTheme', fn ($query) => $query->where('subject_id', $english->id))
                ->get()
                ->each(function (Exercise $exercise) {
                    $content = $exercise->content;
                    unset($content['image_url'], $content['illustration']);
                    $exercise->update(['content' => $content]);
                });

            $plant = Exercise::findOrFail(52);
            $content = $plant->content;
            $content['image_url'] = '/storage/images/edu/plant-parts-class4-v2.jpg';
            unset($content['illustration']);
            $plant->update(['content' => $content]);

            ExerciseMediaAsset::updateOrCreate(
                ['exercise_id' => $plant->id, 'file_path' => 'images/edu/plant-parts-class4-v2.jpg'],
                [
                    'kind' => 'illustration',
                    'alt_text' => 'A complete flowering plant showing roots, stem, leaves, flower and fruit.',
                    'language' => 'en',
                    'metadata' => ['width' => 768, 'height' => 768, 'style_version' => 'edumaison-education-v1'],
                    'sort_order' => 0,
                    'is_active' => true,
                ]
            );
        });
    }
}

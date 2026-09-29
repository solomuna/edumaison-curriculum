<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4EnglishExerciseAuditFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Keep the latest coherent copy (391-398); retain all older rows and attempts as history.
            Exercise::whereIn('id', [
                225, 226, 227, 228, 229, 230, 231, 232,
                308, 309, 310, 311, 312, 313, 314, 315,
            ])->update(['is_active' => false]);

            // These are Mathematics activities stored under English. Keep them for the Math audit.
            Exercise::whereIn('id', [3104, 3105, 3106])->update(['is_active' => false]);

            // This activity belongs to the existing Class 4 Science lesson "Plants and Seeds".
            $plantsLesson = Lesson::query()
                ->where('name', 'Plants and Seeds')
                ->whereHas('unit.integratedTheme.subject', fn ($query) => $query->where('name', 'Science and Technology'))
                ->whereHas('unit.integratedTheme.subject.level', fn ($query) => $query->where('name', 'Class 4'))
                ->firstOrFail();
            $plantExercise = Exercise::findOrFail(52);
            $plantContent = $plantExercise->content;
            unset($plantContent['image_url']);
            $plantExercise->update(['lesson_id' => $plantsLesson->id, 'content' => $plantContent]);

            $this->replaceExplanation(
                1460,
                'A positive statement takes a negative question tag: She is your friend, isn\'t she?'
            );
            $this->replaceExplanation(
                1468,
                'From 8am to 12pm is 4 hours.'
            );
            $this->replaceExplanation(
                395,
                'The superlative form of high is highest: Mount Cameroon is the highest mountain in West Africa.'
            );
        });
    }

    private function replaceExplanation(int $exerciseId, string $explanation): void
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $content = $exercise->content;
        if (isset($content['questions'][0])) {
            $content['questions'][0]['explanation'] = $explanation;
        }
        $exercise->update(['content' => $content]);
    }
}

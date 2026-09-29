<?php

namespace Database\Seeders;

use App\Models\CurriculumDocument;
use App\Models\Level;
use App\Models\SchoolCompetency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4EnglishCompetencyPilotSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $document = CurriculumDocument::where(
                'sha256',
                '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c'
            )->firstOrFail();

            $document->update([
                'authority' => 'Ministry of Basic Education (MINEDUB)',
                'version_label' => '2018',
                'verification_status' => 'verified_source',
                'verified_at' => now(),
            ]);

            $level = Level::where('name', 'Class 4')
                ->where('education_subsystem', 'anglophone')
                ->firstOrFail();
            $subject = $level->subjects()->where('name', 'English')->firstOrFail();

            $competencies = [
                [
                    'name' => 'Listening and speaking in familiar contexts',
                    'description' => 'Understand spoken information, respond appropriately and communicate ideas or feelings clearly.',
                    'source_pages' => '25-26, 41-43',
                    'order' => 1,
                ],
                [
                    'name' => 'Fluent reading and comprehension',
                    'description' => 'Read suitable texts fluently and retrieve or interpret relevant information.',
                    'source_pages' => '25-26, 44',
                    'order' => 2,
                ],
                [
                    'name' => 'Legible and coherent writing',
                    'description' => 'Produce legible, coherent writing that communicates ideas, feelings or information.',
                    'source_pages' => '25-26, 45-49',
                    'order' => 3,
                ],
            ];

            foreach ($competencies as $competency) {
                SchoolCompetency::updateOrCreate(
                    ['subject_id' => $subject->id, 'name' => $competency['name']],
                    $competency + [
                        'curriculum_document_id' => $document->id,
                        'verification_status' => 'verified_source',
                        'is_active' => false,
                    ]
                );
            }
        });
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3ScienceAlignmentPilotSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $documentId = DB::table('curriculum_documents')
                ->where('sha256', '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c')
                ->where('verification_status', 'verified_source')
                ->value('id');
            if (! $documentId) {
                throw new \RuntimeException('The verified MINEDUB Level II document was not found.');
            }

            $subjectId = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 3')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'Science and Technology')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 3 Science and Technology was not found.');
            }

            $themeIds = DB::table('integrated_themes')
                ->where('subject_id', $subjectId)
                ->whereIn('name', ['Health Education', 'Environmental Science', 'Technology'])
                ->pluck('id', 'name');
            if ($themeIds->count() !== 3) {
                throw new \RuntimeException('The Class 3 Science component themes were not found.');
            }

            $movedExerciseIds = [857, 868, 1797, 1798, 1799, 1800, 1808, 1809];
            $currentlyOwnedIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->whereIn('exercises.id', $movedExerciseIds)
                ->pluck('exercises.id')
                ->unique();
            if ($currentlyOwnedIds->count() !== count($movedExerciseIds)) {
                throw new \RuntimeException('The complete Class 3 Science lesson-move set was not found.');
            }

            $healthLessonId = $this->upsertLesson(
                $this->upsertUnit($themeIds['Health Education'], 'drugs-health-hazards-class-3', 'Drugs and Health Hazards', 3),
                'drugs-health-hazards',
                'Drugs and Health Hazards',
                1
            );
            $matterLessonId = $this->upsertLesson(
                $this->upsertUnit($themeIds['Environmental Science'], 'matter-water-class-3', 'Matter and Water', 3),
                'matter-water',
                'Matter and Water',
                1
            );
            $communicationLessonId = $this->upsertLesson(
                $this->upsertUnit($themeIds['Technology'], 'machines-communication-class-3', 'Machines and Communication', 2),
                'machines-communication',
                'Machines and Communication',
                1
            );

            $this->move([857, 1808, 1809], $healthLessonId);
            $this->move([1797, 1798, 1799, 1800], $matterLessonId);
            $this->move([868], $communicationLessonId);

            $competencies = [
                [
                    'official_code' => 'MINEDUB-L2-SCI-C3-HEALTH-BODY',
                    'name' => 'Human body, senses and personal hygiene',
                    'description' => 'Identify body parts, describe the skeleton and bones, explain sense organs and practise personal hygiene.',
                    'source_pages' => '53',
                    'order' => 1,
                    'exercise_ids' => [852, 853, 854],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C3-HEALTH-DISEASES',
                    'name' => 'Reproductive health, diseases and hygiene',
                    'description' => 'Recognise diseases and their transmission and apply preventive and hygiene measures.',
                    'source_pages' => '54',
                    'order' => 2,
                    'exercise_ids' => [855, 856],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C3-HEALTH-SAFETY-FOOD',
                    'name' => 'Drugs, minor accidents, health hazards and food',
                    'description' => 'Use medicines responsibly, identify health hazards, apply basic first aid and explain food classes and importance.',
                    'source_pages' => '55',
                    'order' => 3,
                    'exercise_ids' => [857, 1808, 1809],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C3-ENV-LIVING',
                    'name' => 'Immediate environment, living things and animals',
                    'description' => 'Classify living and non-living things and describe animal habitats, movement, nutrition and care.',
                    'source_pages' => '56',
                    'order' => 4,
                    'exercise_ids' => [858, 859, 860, 861],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C3-ENV-BIRDS-PLANTS',
                    'name' => 'Birds, fish, insects, plants and seed dispersal',
                    'description' => 'Describe birds, fish, insects and plants and explain seeds and their dispersal.',
                    'source_pages' => '57',
                    'order' => 5,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C3-ENV-MATTER',
                    'name' => 'Matter, water, pollution and soils',
                    'description' => 'Describe states of matter and water, distinguish pollution and waste, and identify soil types and characteristics.',
                    'source_pages' => '58',
                    'order' => 6,
                    'exercise_ids' => [862, 863, 864, 1797, 1798, 1799, 1800],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C3-TECH-SYSTEMS',
                    'name' => 'Machines, construction, plumbing and telecommunications',
                    'description' => 'Identify tools and machines and describe basic construction, plumbing and telecommunication systems.',
                    'source_pages' => '59',
                    'order' => 7,
                    'exercise_ids' => [868],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C3-TECH-ENERGY',
                    'name' => 'Energy, electricity and safety',
                    'description' => 'Identify energy sources and uses, recognise electrical devices and apply safety rules.',
                    'source_pages' => '60',
                    'order' => 8,
                    'exercise_ids' => [865, 866, 867],
                ],
            ];

            $allExerciseIds = collect($competencies)->pluck('exercise_ids')->flatten()->unique()->values();
            $ownedExerciseIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->where('exercises.is_active', true)
                ->whereIn('exercises.id', $allExerciseIds)
                ->pluck('exercises.id')
                ->unique();
            if ($ownedExerciseIds->count() !== $allExerciseIds->count()) {
                throw new \RuntimeException('The complete Class 3 Science competency-link set was not found.');
            }

            foreach ($competencies as $competency) {
                $exerciseIds = $competency['exercise_ids'];
                unset($competency['exercise_ids']);
                $competencyId = DB::table('school_competencies')
                    ->where('subject_id', $subjectId)
                    ->where('official_code', $competency['official_code'])
                    ->value('id');
                $values = $competency + [
                    'curriculum_document_id' => $documentId,
                    'verification_status' => 'verified_source',
                    'is_active' => true,
                    'updated_at' => now(),
                ];
                if ($competencyId) {
                    DB::table('school_competencies')->where('id', $competencyId)->update($values);
                } else {
                    $competencyId = DB::table('school_competencies')->insertGetId($values + [
                        'subject_id' => $subjectId,
                        'created_at' => now(),
                    ]);
                }

                foreach ($exerciseIds as $exerciseId) {
                    DB::table('exercise_school_competency')->updateOrInsert([
                        'exercise_id' => $exerciseId,
                        'school_competency_id' => $competencyId,
                    ]);
                }
            }
        });
    }

    private function upsertUnit(int $themeId, string $slug, string $name, int $order): int
    {
        $id = DB::table('units')->where(['integrated_theme_id' => $themeId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => $name.' for Class 3 Science and Technology.',
            'summary' => $name.' aligned to the official MINEDUB Level II curriculum.',
            'order' => $order,
            'estimated_weeks' => 2,
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($id) {
            DB::table('units')->where('id', $id)->update($values);
            return $id;
        }
        return DB::table('units')->insertGetId($values + [
            'integrated_theme_id' => $themeId,
            'slug' => $slug,
            'created_at' => now(),
        ]);
    }

    private function upsertLesson(int $unitId, string $slug, string $name, int $order): int
    {
        $id = DB::table('lessons')->where(['unit_id' => $unitId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => $name.' activities for Class 3.',
            'content' => $name.' practice aligned to the official curriculum.',
            'order' => $order,
            'estimated_minutes' => 30,
            'type' => 'science',
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($id) {
            DB::table('lessons')->where('id', $id)->update($values);
            return $id;
        }
        return DB::table('lessons')->insertGetId($values + [
            'unit_id' => $unitId,
            'slug' => $slug,
            'created_at' => now(),
        ]);
    }

    private function move(array $exerciseIds, int $lessonId): void
    {
        DB::table('exercises')->whereIn('id', $exerciseIds)->update([
            'lesson_id' => $lessonId,
            'updated_at' => now(),
        ]);
    }
}

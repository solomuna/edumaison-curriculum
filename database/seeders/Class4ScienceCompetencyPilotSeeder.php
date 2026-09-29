<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4ScienceCompetencyPilotSeeder extends Seeder
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
                ->where('levels.name', 'Class 4')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'Science and Technology')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 4 Science and Technology was not found.');
            }

            $competencies = [
                [
                    'official_code' => 'MINEDUB-L2-SCI-C4-HEALTH-BODY',
                    'name' => 'Human body, senses and healthy practices',
                    'description' => 'Associate body parts with their functions, explain and use the sense organs, and apply healthy practices.',
                    'source_pages' => '53-54',
                    'order' => 1,
                    'exercise_ids' => [431,432,433,434,435,436,2164,2165,2166,2167,2168,2169,2170,2171,2172,2173,2174,2175,2183,2186],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C4-HEALTH-DISEASES',
                    'name' => 'Disease transmission and prevention',
                    'description' => 'Distinguish water-borne and insect-borne diseases, explain transmission, and apply preventive measures.',
                    'source_pages' => '54',
                    'order' => 2,
                    'exercise_ids' => [437,438,439,440,441,2176,2177,2178,2179,2180,2181],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C4-HEALTH-SAFETY-FOOD',
                    'name' => 'Minor accidents, health hazards and balanced diet',
                    'description' => 'Apply basic first aid, identify health hazards, and explain balanced food choices and cooking practices.',
                    'source_pages' => '55',
                    'order' => 3,
                    'exercise_ids' => [442,443,444,445,2182,2184,2185,2187,2188,2189,2190,2191,2192],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C4-ENV-ANIMALS',
                    'name' => 'Immediate environment and animal life',
                    'description' => 'Classify living and non-living things and describe animal habitats, adaptation, care and protection.',
                    'source_pages' => '56',
                    'order' => 4,
                    'exercise_ids' => [2193,2194,2195,2196,2197],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C4-ENV-BIRDS-PLANTS',
                    'name' => 'Birds, fishing, insects, plants and greenery',
                    'description' => 'Differentiate birds, fish and insects, describe related tools or methods, and care for plants and flowers.',
                    'source_pages' => '57',
                    'order' => 5,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C4-ENV-WATER',
                    'name' => 'Water, pollution and environmental protection',
                    'description' => 'Protect water sources, distinguish pollution and waste, and practise environmental protection.',
                    'source_pages' => '58',
                    'order' => 6,
                    'exercise_ids' => [2211,2213,2214],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C4-TECH-SYSTEMS',
                    'name' => 'Machines, construction, plumbing and telecommunications',
                    'description' => 'Differentiate manual and electrical machines and describe basic construction, plumbing and telecommunication systems.',
                    'source_pages' => '59',
                    'order' => 7,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SCI-C4-TECH-ENERGY',
                    'name' => 'Energy, electricity and safety',
                    'description' => 'Describe energy sources and uses, identify conductors and insulators, and apply electrical safety rules.',
                    'source_pages' => '60',
                    'order' => 8,
                    'exercise_ids' => [2227,2228,2229,2230,2231,2232,2233,2234,2235,2236,2237,2238,2239],
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
                throw new \RuntimeException('The complete Class 4 Science competency-link set was not found.');
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
}

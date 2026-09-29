<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3SocialStudiesAlignmentPilotSeeder extends Seeder
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
                ->where('subjects.name', 'Social Studies')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 3 Social Studies was not found.');
            }

            $expectedExerciseIds = array_merge(range(1333, 1342), range(2832, 2848));
            $ownedExerciseIds = $this->ownedActiveExerciseIds($subjectId, $expectedExerciseIds);
            if ($ownedExerciseIds->count() !== count($expectedExerciseIds)) {
                throw new \RuntimeException('The complete active Class 3 Social Studies exercise set was not found.');
            }

            $irrelevantImages = [
                1338 => '/storage/images/edu/arm.png',
                1340 => '/storage/images/edu/cat.jpg',
                1341 => '/storage/images/edu/ice.png',
                2833 => '/storage/images/edu/ant.png',
                2834 => '/storage/images/edu/table.png',
                2837 => '/storage/images/edu/ice.png',
                2839 => '/storage/images/edu/well.png',
                2846 => '/storage/images/edu/cat.jpg',
            ];
            $imageRows = DB::table('exercises')->whereIn('id', array_keys($irrelevantImages))->get()->keyBy('id');
            foreach ($irrelevantImages as $exerciseId => $expectedImage) {
                $content = json_decode($imageRows[$exerciseId]->content, true, flags: JSON_THROW_ON_ERROR);
                $currentImage = $content['image_url'] ?? null;
                if ($currentImage !== null && $currentImage !== $expectedImage) {
                    throw new \RuntimeException("Exercise {$exerciseId} now references an unexpected image.");
                }
                unset($content['image_url']);
                DB::table('exercises')->where('id', $exerciseId)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                    'updated_at' => now(),
                ]);
            }

            $geographyThemeId = DB::table('integrated_themes')
                ->where('subject_id', $subjectId)
                ->where('name', 'Cameroon Geography')
                ->value('id');
            if (! $geographyThemeId) {
                throw new \RuntimeException('The Class 3 Cameroon Geography theme was not found.');
            }

            $humanGeographyLessonId = $this->upsertLesson(
                $this->upsertUnit($geographyThemeId, 'human-geography-class-3', 'Human Geography', 2),
                'peoples-and-ethnic-groups-class-3',
                'Peoples and Ethnic Groups',
                1
            );

            DB::table('exercises')->whereIn('id', [1337, 1338])->update([
                'lesson_id' => $humanGeographyLessonId,
                'updated_at' => now(),
            ]);

            $competencies = [
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-HISTORY',
                    'name' => 'History, time, sources and historical events',
                    'description' => 'State types and sources of history, relate past, present and future events, and recount historical figures and events.',
                    'source_pages' => '71',
                    'order' => 1,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-GEO-PHYSICAL',
                    'name' => 'Physical geography and weather',
                    'description' => 'Identify places in the immediate environment, use maps, observe weather elements and predict weather conditions.',
                    'source_pages' => '72',
                    'order' => 2,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-GEO-ECONOMIC',
                    'name' => 'Economic activities in the locality',
                    'description' => 'Describe farming, trading and fishing in the locality and carry out activities for subsistence.',
                    'source_pages' => '72',
                    'order' => 3,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-GEO-HUMAN',
                    'name' => 'Human geography and ethnic groups',
                    'description' => 'Identify ethnic groups in the locality, describe their seasonal activities and respect every person’s origin.',
                    'source_pages' => '73',
                    'order' => 4,
                    'exercise_ids' => [1337, 1338],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-EMBLEMS',
                    'name' => 'National emblems',
                    'description' => 'Explain the importance of national emblems, sing the National Anthem and practise love for the nation.',
                    'source_pages' => '73',
                    'order' => 5,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-RULES',
                    'name' => 'Rules and regulations',
                    'description' => 'Follow rules and regulations at home, at school, in the community and in the state.',
                    'source_pages' => '73',
                    'order' => 6,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-INSTITUTIONS',
                    'name' => 'State institutions and personalities',
                    'description' => 'Identify state and local authorities and explain administrative, traditional and religious structures.',
                    'source_pages' => '74',
                    'order' => 7,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-ELECTIONS',
                    'name' => 'Elections and fair decisions',
                    'description' => 'Describe types of elections, explain the electoral process and cherish fair decisions.',
                    'source_pages' => '74',
                    'order' => 8,
                    'exercise_ids' => [1340, 2843, 2844, 2845],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-INTERNATIONAL',
                    'name' => 'National and international institutions',
                    'description' => 'Recognise national and international institutions that assist schools and explain how they assist others.',
                    'source_pages' => '74',
                    'order' => 9,
                    'exercise_ids' => [2846, 2847, 2848],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-MORAL-VALUES',
                    'name' => 'Universal values and responsible citizenship',
                    'description' => 'Practise simple etiquette, explain ethical values and promote responsible citizenship.',
                    'source_pages' => '75',
                    'order' => 10,
                    'exercise_ids' => [2832, 2833, 2834],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-MORAL-COMMON-GOOD',
                    'name' => 'The common good',
                    'description' => 'Identify public property, use community property appropriately and volunteer for the family and community.',
                    'source_pages' => '75',
                    'order' => 11,
                    'exercise_ids' => [2835, 2836],
                ],
            ];

            $linkedExerciseIds = collect($competencies)->pluck('exercise_ids')->flatten()->unique()->values();
            if ($this->ownedActiveExerciseIds($subjectId, $linkedExerciseIds->all())->count() !== $linkedExerciseIds->count()) {
                throw new \RuntimeException('The complete Class 3 Social Studies competency-link set was not found.');
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

    private function ownedActiveExerciseIds(int $subjectId, array $exerciseIds)
    {
        return DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->where('integrated_themes.subject_id', $subjectId)
            ->where('exercises.is_active', true)
            ->whereIn('exercises.id', $exerciseIds)
            ->pluck('exercises.id')
            ->unique();
    }

    private function upsertUnit(int $themeId, string $slug, string $name, int $order): int
    {
        $id = DB::table('units')->where(['integrated_theme_id' => $themeId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => 'Human geography for Class 3 Social Studies.',
            'summary' => 'Peoples, ethnic groups and seasonal activities aligned to the official curriculum.',
            'order' => $order,
            'estimated_weeks' => 1,
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
            'description' => 'Identify peoples and ethnic groups and respect everyone’s origin.',
            'content' => 'Human geography practice aligned to the official MINEDUB Level II curriculum.',
            'order' => $order,
            'estimated_minutes' => 30,
            'type' => 'mixed',
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
}

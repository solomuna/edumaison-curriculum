<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3PhysicalEducationAlignmentPilotSeeder extends Seeder
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
                ->where('subjects.name', 'Physical Education')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 3 Physical Education was not found.');
            }

            $expectedIds = array_merge(range(1505, 1508), range(2106, 2111));
            if ($this->ownedExerciseIds($subjectId, $expectedIds)->count() !== count($expectedIds)) {
                throw new \RuntimeException('The complete Class 3 Physical Education exercise set was not found.');
            }

            if (DB::table('exercise_attempts')->where('exercise_id', 2106)->exists()) {
                throw new \RuntimeException('The duplicate football-team exercise now has attempts.');
            }
            $this->assertFootballTeamDuplicate();
            DB::table('exercises')->where('id', 2106)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);

            $this->removeExpectedImages([
                1506 => '/storage/images/edu/cat.jpg',
                1508 => '/storage/images/edu/cat.jpg',
                2106 => '/storage/images/edu/cat.jpg',
                2108 => '/storage/images/edu/cat.jpg',
            ]);
            $this->correctExerciseContent();

            $teamSportsLessonId = DB::table('lessons')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->where('lessons.id', 260)
                ->value('lessons.id');
            if (! $teamSportsLessonId) {
                throw new \RuntimeException('The Class 3 team-sports lesson was not found.');
            }
            DB::table('lessons')->where('id', $teamSportsLessonId)->update([
                'name' => 'Rules and Roles in Team Sports',
                'description' => 'Handle a ball, follow basic rules, identify player roles and play with teammates.',
                'content' => 'Team-sports rules and roles aligned to the official MINEDUB Level II curriculum.',
                'updated_at' => now(),
            ]);
            DB::table('exercises')->whereIn('id', [2109, 2110, 2111])->update([
                'lesson_id' => $teamSportsLessonId,
                'updated_at' => now(),
            ]);

            $competencies = [
                [
                    'official_code' => 'MINEDUB-L2-PE-C3-RHYTHMIC',
                    'name' => 'Movement and rhythmic activities',
                    'description' => 'Develop muscles through balancing, matching, galloping, hopscotch, folk dance, ballet and racing calisthenics.',
                    'source_pages' => '83',
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-PE-C3-RELAYS',
                    'name' => 'Relays',
                    'description' => 'Develop a healthy, graceful and balanced body through touch-and-run, line picking, tunnel, zigzag and shuttle relays.',
                    'source_pages' => '83',
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-PE-C3-SPRINTS',
                    'name' => 'Sprints from 20 to 40 metres',
                    'description' => 'Run faster over a given distance and maintain speed through the required distance.',
                    'source_pages' => '83',
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-PE-C3-JUMPS',
                    'name' => 'Rope, high and long jumps',
                    'description' => 'Run to cross an obstacle and raise the legs with the hands up to jump over an obstacle.',
                    'source_pages' => '83',
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-PE-C3-THROWS',
                    'name' => 'Throws',
                    'description' => 'Combine actions to manipulate and project objects, handle familiar weights and identify a good throwing position.',
                    'source_pages' => '84',
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-PE-C3-TEAM-SPORTS',
                    'name' => 'Team sports rules and player roles',
                    'description' => 'Handle a ball in team sports, follow basic game rules, identify player roles and play with teammates.',
                    'source_pages' => '84',
                    'exercise_ids' => [1505, 1506, 1507, 1508, 2109, 2110, 2111],
                ],
                [
                    'official_code' => 'MINEDUB-L2-PE-C3-GYMNASTICS',
                    'name' => 'Gymnastics postures and balance',
                    'description' => 'Use different postures to keep stability and move using hands, head and feet.',
                    'source_pages' => '84',
                    'exercise_ids' => [],
                ],
            ];

            $linkedIds = collect($competencies)->pluck('exercise_ids')->flatten()->unique()->values();
            if ($this->ownedActiveExerciseIds($subjectId, $linkedIds->all())->count() !== $linkedIds->count()) {
                throw new \RuntimeException('The Physical Education competency link set is incomplete.');
            }

            foreach ($competencies as $order => $competency) {
                $exerciseIds = $competency['exercise_ids'];
                unset($competency['exercise_ids']);
                $competencyId = DB::table('school_competencies')
                    ->where('subject_id', $subjectId)
                    ->where('official_code', $competency['official_code'])
                    ->value('id');
                $values = $competency + [
                    'order' => $order + 1,
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

    private function ownedExerciseIds(int $subjectId, array $exerciseIds)
    {
        return DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->where('integrated_themes.subject_id', $subjectId)
            ->whereIn('exercises.id', $exerciseIds)
            ->pluck('exercises.id')
            ->unique();
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

    private function assertFootballTeamDuplicate(): void
    {
        $rows = DB::table('exercises')->whereIn('id', [1505, 2106])->get(['id', 'content'])->keyBy('id');
        $first = json_decode($rows[1505]->content, true, flags: JSON_THROW_ON_ERROR);
        $second = json_decode($rows[2106]->content, true, flags: JSON_THROW_ON_ERROR);
        $firstQuestion = $first['questions'][0] ?? [];
        $secondQuestion = $second['questions'][0] ?? [];
        if (($firstQuestion['options'] ?? null) !== ($secondQuestion['options'] ?? null)
            || ($firstQuestion['answer'] ?? null) !== ($secondQuestion['answer'] ?? null)
            || ($firstQuestion['options'][2] ?? null) !== '11') {
            throw new \RuntimeException('The expected football-team duplicate pair has changed.');
        }
    }

    private function removeExpectedImages(array $expectedImages): void
    {
        $rows = DB::table('exercises')->whereIn('id', array_keys($expectedImages))->get()->keyBy('id');
        foreach ($expectedImages as $exerciseId => $expectedImage) {
            $content = json_decode($rows[$exerciseId]->content, true, flags: JSON_THROW_ON_ERROR);
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
    }

    private function correctExerciseContent(): void
    {
        $this->replaceContent(1506, 'Offside rule', 'Corner kick goal', function (array $content): array {
            $oldQuestion = $content['questions'][0]['text'] ?? null;
            $newQuestion = 'Can a goal be scored directly against the opposing team from a corner kick?';
            if (! in_array($oldQuestion, ['A goal scored directly from a corner kick is ___.', $newQuestion], true)) {
                throw new \RuntimeException('Exercise 1506 has changed.');
            }
            $content['questions'][0] = [
                'text' => $newQuestion,
                'answer' => 0,
                'options' => ['Yes', 'No'],
            ];
            return $content;
        });

        $this->replaceContent(1507, 'Football fact', 'Goalkeeper handling', function (array $content): array {
            $old = 'The goalkeeper is the only player allowed to use hands.';
            $new = 'During normal play, a goalkeeper may handle the ball inside their own penalty area.';
            if (! in_array($content['statement'] ?? null, [$old, $new], true)) {
                throw new \RuntimeException('Exercise 1507 has changed.');
            }
            $content['statement'] = $new;
            $content['answer'] = true;
            return $content;
        });

        $this->replaceContent(2109, 'Sports Rules', 'When a goal is scored', function (array $content): array {
            $old = 'In football, a goal is scored when the ball crosses the goal line.';
            $new = 'In football, a goal is scored when the whole ball crosses the goal line between the posts and under the crossbar.';
            if (! in_array($content['statement'] ?? null, [$old, $new], true)) {
                throw new \RuntimeException('Exercise 2109 has changed.');
            }
            $content['statement'] = $new;
            $content['answer'] = true;
            return $content;
        });

        $this->replaceContent(2110, 'Fill: Team Sports', 'Fill: Team Sports', function (array $content): array {
            $oldSentence = 'In basketball, the ball is put into play with a ___ jump.';
            $newSentence = 'A basketball game begins with a ___.';
            $current = $content['items'][0]['sentence'] ?? null;
            if (! in_array($current, [$oldSentence, $newSentence], true)) {
                throw new \RuntimeException('Exercise 2110 has changed.');
            }
            $content['items'][0] = ['answer' => 'jump ball', 'sentence' => $newSentence];
            return $content;
        });
    }

    private function replaceContent(int $exerciseId, string $oldTitle, string $newTitle, callable $transform): void
    {
        $row = DB::table('exercises')->where('id', $exerciseId)->first(['title', 'content']);
        if (! $row || ! in_array($row->title, [$oldTitle, $newTitle], true)) {
            throw new \RuntimeException("Exercise {$exerciseId} title has changed.");
        }
        $content = $transform(json_decode($row->content, true, flags: JSON_THROW_ON_ERROR));
        DB::table('exercises')->where('id', $exerciseId)->update([
            'title' => $newTitle,
            'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'updated_at' => now(),
        ]);
    }
}

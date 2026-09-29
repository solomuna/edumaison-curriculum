<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3IctAlignmentPilotSeeder extends Seeder
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
                ->where('subjects.name', 'ICT')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 3 ICT was not found.');
            }

            $exerciseIds = range(711, 721);
            if ($this->ownedActiveExerciseIds($subjectId, $exerciseIds)->count() !== count($exerciseIds)) {
                throw new \RuntimeException('The complete active Class 3 ICT exercise set was not found.');
            }

            $this->removeExpectedImages([
                711 => '/storage/images/edu/computer.png',
                712 => '/storage/images/edu/computer.png',
                715 => '/storage/images/edu/computer.png',
                716 => '/storage/images/edu/stem.png',
                718 => '/storage/images/edu/computer.png',
                719 => '/storage/images/edu/computer.png',
            ]);
            $this->correctMatchPartsPrompt();

            $basicThemeId = DB::table('integrated_themes')
                ->where('subject_id', $subjectId)
                ->where('id', 90)
                ->value('id');
            $internetThemeId = DB::table('integrated_themes')
                ->where('subject_id', $subjectId)
                ->where('id', 91)
                ->value('id');
            if (! $basicThemeId || ! $internetThemeId) {
                throw new \RuntimeException('The Class 3 ICT themes were not found.');
            }

            DB::table('integrated_themes')->where('id', $basicThemeId)->update([
                'name' => 'Basic Knowledge of a Computer System and ICT Tools',
                'updated_at' => now(),
            ]);
            DB::table('lessons')->where('id', 151)->where('unit_id', 105)->update([
                'name' => 'Computer Components and Functions',
                'description' => 'Identify computer-system components and describe their roles and importance.',
                'content' => 'Computer components aligned to the official MINEDUB Level II ICT curriculum.',
                'updated_at' => now(),
            ]);
            DB::table('units')->where('id', 106)->where('integrated_theme_id', $basicThemeId)->update([
                'name' => 'Software and Productivity Tools',
                'description' => 'Software-requiring devices, computer uses, keyboard, mouse and word processing.',
                'summary' => 'Official Class 3 ICT tools and productivity content.',
                'updated_at' => now(),
            ]);
            DB::table('lessons')->where('id', 152)->where('unit_id', 106)->update([
                'name' => 'Software and Programs',
                'description' => 'Recognise that computers, mobile telephones and tablets require software to function.',
                'content' => 'Software concepts aligned to the official Class 3 ICT curriculum.',
                'updated_at' => now(),
            ]);
            $wordLessonId = $this->upsertLesson(
                106,
                'word-processor-class-3',
                'Word Processor',
                'Identify and load a word processor and recognise its basic functions and toolbar.',
                2
            );
            DB::table('exercises')->where('id', 717)->update([
                'lesson_id' => $wordLessonId,
                'updated_at' => now(),
            ]);

            DB::table('units')->where('id', 107)->where('integrated_theme_id', $internetThemeId)->update([
                'name' => 'Information, Browsers and Social Media',
                'description' => 'Reliable information, web browsers, privacy and acceptable social-media conduct.',
                'summary' => 'Official Class 3 internet and communication content.',
                'updated_at' => now(),
            ]);
            DB::table('lessons')->where('id', 153)->where('unit_id', 107)->update([
                'name' => 'Web Browsers and Information',
                'description' => 'Identify browsers and use them to search for useful information.',
                'content' => 'Web browsers and information sources aligned to the official Class 3 ICT curriculum.',
                'updated_at' => now(),
            ]);
            $socialLessonId = $this->upsertLesson(
                107,
                'social-media-conduct-class-3',
                'Social Media and Privacy',
                'Recognise social-media tools, communicate responsibly and protect private information.',
                2
            );
            DB::table('exercises')->where('id', 721)->update([
                'lesson_id' => $socialLessonId,
                'updated_at' => now(),
            ]);

            $competencies = [
                ['MINEDUB-L2-ICT-C3-COMPONENTS', 'Computer-system components', 'Describe the role and importance of the keyboard, mouse, monitor, printer, scanner, external drives, speakers and microphones.', '89', [711, 713]],
                ['MINEDUB-L2-ICT-C3-INBUILT', 'Computers with inbuilt components', 'Identify tablets, laptops and personal digital assistants and distinguish them from other computer types.', '89', []],
                ['MINEDUB-L2-ICT-C3-SOFTWARE-USES', 'Software-requiring devices and computer uses', 'Identify ICT devices that require software, explain how they function and describe responsible uses of computers in school and society.', '90', [714, 718]],
                ['MINEDUB-L2-ICT-C3-KEYBOARD', 'Keyboard use', 'Use the space bar, backspace, lower-case and upper-case characters to enter letters and figures.', '90', [712]],
                ['MINEDUB-L2-ICT-C3-MOUSE', 'Mouse functions', 'Describe mouse functions and select and drag desktop objects.', '91', [715]],
                ['MINEDUB-L2-ICT-C3-WORD-PROCESSOR', 'Word processor', 'Identify and load a word processor and recognise its basic functions and toolbar.', '91', [717]],
                ['MINEDUB-L2-ICT-C3-INFORMATION', 'Reliable information sources and privacy', 'Identify information sources, explain why credible information comes from reliable sources and respect privacy.', '92', []],
                ['MINEDUB-L2-ICT-C3-BROWSERS', 'Web browsers', 'Identify web browsers and explain that they are used to search for useful information.', '92', [719, 720]],
                ['MINEDUB-L2-ICT-C3-SOCIAL-MEDIA', 'Social media and acceptable conduct', 'Identify social-media icons, communicate with social media and state acceptable conduct for internet and social-media users.', '92', [721]],
                ['MINEDUB-L2-ICT-C3-HEALTH-SAFETY', 'Health and safety around ICT', 'State the dangers of poor sitting position and health problems affecting the eyes when using computer and television screens.', '93', []],
            ];

            $linkedIds = collect($competencies)->pluck(4)->flatten()->unique()->values();
            if ($this->ownedActiveExerciseIds($subjectId, $linkedIds->all())->count() !== $linkedIds->count()) {
                throw new \RuntimeException('The ICT competency link set is incomplete.');
            }

            foreach ($competencies as $order => [$code, $name, $description, $pages, $linkedExerciseIds]) {
                $competencyId = DB::table('school_competencies')
                    ->where('subject_id', $subjectId)
                    ->where('official_code', $code)
                    ->value('id');
                $values = [
                    'name' => $name,
                    'description' => $description,
                    'source_pages' => $pages,
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
                        'official_code' => $code,
                        'created_at' => now(),
                    ]);
                }

                foreach ($linkedExerciseIds as $exerciseId) {
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

    private function correctMatchPartsPrompt(): void
    {
        $row = DB::table('exercises')->where('id', 713)->first(['content']);
        $content = json_decode($row->content, true, flags: JSON_THROW_ON_ERROR);
        $old = 'Match each part to what it does.';
        $new = 'Match each computer part to its picture.';
        if (! in_array($content['question'] ?? null, [$old, $new], true)) {
            throw new \RuntimeException('Exercise 713 prompt has changed.');
        }
        $expectedWords = ['Mouse', 'Keyboard', 'Monitor', 'Printer'];
        if (collect($content['pairs'] ?? [])->pluck('word')->all() !== $expectedWords) {
            throw new \RuntimeException('Exercise 713 drawing pairs have changed.');
        }
        $content['question'] = $new;
        DB::table('exercises')->where('id', 713)->update([
            'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'updated_at' => now(),
        ]);
    }

    private function upsertLesson(int $unitId, string $slug, string $name, string $description, int $order): int
    {
        $id = DB::table('lessons')->where(['unit_id' => $unitId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => $description,
            'content' => "{$name} practice aligned to the official MINEDUB Level II ICT curriculum.",
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

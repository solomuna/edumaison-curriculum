<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3VocationalArtsAlignmentPilotSeeder extends Seeder
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

            $subjectIds = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 3')
                ->where('levels.education_subsystem', 'anglophone')
                ->whereIn('subjects.name', ['Arts and Crafts', 'Vocational Studies'])
                ->pluck('subjects.id', 'subjects.name');
            if ($subjectIds->count() !== 2) {
                throw new \RuntimeException('The Class 3 Arts and Vocational subjects were not found.');
            }

            $artsId = (int) $subjectIds['Arts and Crafts'];
            $vocationalId = (int) $subjectIds['Vocational Studies'];
            $expectedIds = array_merge(range(2797, 2809), range(2956, 2977), [1501, 1502, 1503, 1504, 2070]);
            if ($this->ownedExerciseIds([$artsId, $vocationalId], $expectedIds)->count() !== count($expectedIds)) {
                throw new \RuntimeException('The expected Class 3 Arts and Vocational exercise set was not found.');
            }

            if (DB::table('exercise_attempts')->where('exercise_id', 2805)->exists()) {
                throw new \RuntimeException('The duplicate carpentry exercise now has attempts and cannot be deactivated.');
            }

            $duplicate = DB::table('exercises')->where('id', 2805)->first(['content', 'is_active']);
            $canonical = DB::table('exercises')->where('id', 2958)->first(['content', 'is_active']);
            if (! $duplicate || ! $canonical || $duplicate->content !== $canonical->content || ! $canonical->is_active) {
                throw new \RuntimeException('The expected carpentry duplicate pair has changed.');
            }
            DB::table('exercises')->where('id', 2805)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);

            $irrelevantImages = [
                2801 => '/storage/images/edu/cat.jpg',
                2802 => '/storage/images/edu/cat.jpg',
                2804 => '/storage/images/edu/car.jpg',
                2806 => '/storage/images/edu/arm.png',
                2807 => '/storage/images/edu/arm.png',
                2808 => '/storage/images/edu/arm.png',
                2959 => '/storage/images/edu/paintbrush.png',
                2960 => '/storage/images/edu/cat.jpg',
                2962 => '/storage/images/edu/river.png',
                2964 => '/storage/images/edu/cat.jpg',
                2966 => '/storage/images/edu/paintbrush.png',
                2969 => '/storage/images/edu/ant.png',
                2970 => '/storage/images/edu/phone.png',
                2971 => '/storage/images/edu/saw.png',
                2972 => '/storage/images/edu/cat.jpg',
                2973 => '/storage/images/edu/cat.jpg',
                2975 => '/storage/images/edu/cat.jpg',
                2976 => '/storage/images/edu/ant.png',
            ];
            $this->removeExpectedImages($irrelevantImages);
            $this->correctMvetQuestion();

            $vocationalCraftThemeId = DB::table('integrated_themes')
                ->where('subject_id', $vocationalId)
                ->where('id', 252)
                ->value('id');
            if (! $vocationalCraftThemeId) {
                throw new \RuntimeException('The Class 3 Vocational craft theme was not found.');
            }
            DB::table('integrated_themes')->where('id', $vocationalCraftThemeId)->update([
                'name' => 'Arts and Crafts',
                'updated_at' => now(),
            ]);

            DB::table('units')->where('id', 357)->where('integrated_theme_id', $vocationalCraftThemeId)->update([
                'name' => 'Toolbox and Safe Tool Use',
                'description' => 'Identify, use, clean and safely store tools used by Class 3 craft workers.',
                'summary' => 'Official Class 3 toolbox practice for carpentry and weaving.',
                'updated_at' => now(),
            ]);
            DB::table('lessons')->where('id', 425)->where('unit_id', 357)->update([
                'name' => 'Using and Caring for Craft Tools',
                'description' => 'Identify and safely use tools for carpentry and weaving.',
                'content' => 'Tool recognition, safe use, cleaning and storage aligned to the MINEDUB Level II curriculum.',
                'updated_at' => now(),
            ]);

            $craftProductionLessonId = $this->upsertLesson(
                $this->upsertUnit($vocationalCraftThemeId, 'craft-production-class-3', 'Craft Production', 2),
                'produce-and-decorate-crafts-class-3',
                'Produce and Decorate Crafts',
                'Use moulding, carpentry, weaving, decoration, sculpting and painting to produce simple objects.',
                1
            );
            DB::table('exercises')->whereIn('id', [2956, 2957, 2958, 2968])->update([
                'lesson_id' => 425,
                'updated_at' => now(),
            ]);
            DB::table('exercises')->whereIn('id', [1501, 1502, 1503, 2969])->update([
                'lesson_id' => $craftProductionLessonId,
                'updated_at' => now(),
            ]);

            $visualThemeId = DB::table('integrated_themes')
                ->where('subject_id', $artsId)
                ->where('id', 178)
                ->value('id');
            if (! $visualThemeId) {
                throw new \RuntimeException('The Class 3 Arts theme was not found.');
            }
            DB::table('integrated_themes')->where('id', $visualThemeId)->update([
                'name' => 'Visual Arts',
                'updated_at' => now(),
            ]);
            DB::table('units')->where('id', 211)->where('integrated_theme_id', $visualThemeId)->update([
                'name' => 'Painting, Photography and Architecture',
                'description' => 'Official Class 3 visual arts: painting, photography and architecture.',
                'summary' => 'Visual arts practice aligned to the MINEDUB Level II curriculum.',
                'updated_at' => now(),
            ]);
            DB::table('lessons')->where('id', 259)->where('unit_id', 211)->update([
                'name' => 'Painting and Colour',
                'description' => 'Identify painting materials and paint using appropriate colour combinations.',
                'content' => 'Painting practice aligned to the official Class 3 Visual Arts outcomes.',
                'updated_at' => now(),
            ]);
            DB::table('exercises')->where('id', 2070)->update(['lesson_id' => 259, 'updated_at' => now()]);
            DB::table('exercises')->where('id', 1504)->update(['lesson_id' => 404, 'updated_at' => now()]);

            $performingThemeId = $this->upsertTheme($artsId, 'performing-arts-class-3', 'Performing Arts', 2);
            $musicUnitId = $this->upsertUnit($performingThemeId, 'music-class-3', 'Music', 1);
            $instrumentLessonId = $this->upsertLesson(
                $musicUnitId,
                'musical-instruments-and-sol-fa-class-3',
                'Musical Instruments and Sol-fa',
                'Recognise musical instruments, rhythms, melodies, parts and sol-fa notation.',
                1
            );
            $performanceLessonId = $this->upsertLesson(
                $musicUnitId,
                'music-performance-class-3',
                'Music Performance',
                'Play, sing and produce music for entertainment while respecting pitch and intonation.',
                2
            );
            DB::table('exercises')->whereIn('id', range(2970, 2974))->update([
                'lesson_id' => $instrumentLessonId,
                'updated_at' => now(),
            ]);
            DB::table('exercises')->whereIn('id', range(2975, 2977))->update([
                'lesson_id' => $performanceLessonId,
                'updated_at' => now(),
            ]);

            $vocationalCompetencies = [
                ['MINEDUB-L2-VOC-C3-NEEDLEWORK', 'Needle work and openings', 'Identify, practise, manage and store needle-work equipment and sew simple openings.', '77', []],
                ['MINEDUB-L2-VOC-C3-FASTENINGS', 'Fastenings', 'Identify and use buttons, buttonholes, hooks, eyes and zips.', '77', []],
                ['MINEDUB-L2-VOC-C3-FOODS', 'Food preservation, fruits, table setting and meals', 'Conserve food and fruits, identify table items, lay a table and distinguish meals.', '77', []],
                ['MINEDUB-L2-VOC-C3-LAUNDRY', 'Laundry equipment and materials', 'Identify, use and manage laundry equipment and materials.', '77', [2802]],
                ['MINEDUB-L2-VOC-C3-HOUSECRAFT', 'Housecraft equipment and sections of the house', 'Identify, clean and manage housecraft equipment and recognise the main sections of a house.', '77', [2800, 2801]],
                ['MINEDUB-L2-VOC-C3-TOOLBOX', 'Toolbox and safe tool care', 'Identify, use, clean and safely store tools used by moulders, carpenters, weavers, sculptors and painters.', '79', [2803, 2804, 2956, 2957, 2958, 2968]],
                ['MINEDUB-L2-VOC-C3-CRAFTS', 'Craft production and decoration', 'Use suitable materials to produce and decorate objects through moulding, carpentry, weaving, sculpting and painting.', '79', [1501, 1502, 1503, 2969]],
                ['MINEDUB-L2-VOC-C3-FOLDING', 'Folding and cutting objects', 'Fold and cut materials to produce simple objects.', '79', []],
                ['MINEDUB-L2-VOC-C3-AGRI-TOOLS', 'Agricultural tools', 'Identify, use and care for basic agricultural tools.', '80', []],
                ['MINEDUB-L2-VOC-C3-FARMING', 'Farming, gardening, plants and flowers', 'Carry out simple farming and gardening activities involving plants and flowers.', '80', []],
                ['MINEDUB-L2-VOC-C3-SOIL', 'Soil enrichment and compost', 'Identify and apply soil-enrichment practices, including compost preparation.', '80', [2806, 2808, 2809]],
                ['MINEDUB-L2-VOC-C3-GERMINATION', 'Seed germination', 'Observe and practise seed germination.', '80', []],
                ['MINEDUB-L2-VOC-C3-LIVESTOCK', 'Livestock farming', 'Identify and practise basic pig, poultry, cattle, snail and rabbit farming.', '80', []],
            ];
            $artsCompetencies = [
                ['MINEDUB-L2-ART-C3-PAINTING', 'Painting materials and colour', 'Identify painting materials, paint with appropriate colour combinations and appreciate beauty in painting.', '81', [2070]],
                ['MINEDUB-L2-ART-C3-PHOTOGRAPHY', 'Photography', 'Identify photographic devices, use photography to record events and practise photography.', '81', []],
                ['MINEDUB-L2-ART-C3-ARCHITECTURE', 'Architecture and house plans', 'Identify an architect and architectural materials and draw miniature house plans.', '81', []],
                ['MINEDUB-L2-ART-C3-DANCE', 'Dance steps and rhythm', 'Identify dance steps, dance according to local customs and traditions and display self-esteem.', '82', []],
                ['MINEDUB-L2-ART-C3-MUSIC', 'Musical instruments, rhythm and sol-fa', 'Play musical instruments, sing with pitch and intonation, and produce music for entertainment.', '82', [2970, 2971, 2973, 2974]],
                ['MINEDUB-L2-ART-C3-THEATRE', 'Theatre and drama', 'Act scripted roles and demonstrate silent stage performance.', '82', []],
            ];
            $this->upsertCompetencies($documentId, $vocationalId, $vocationalCompetencies);
            $this->upsertCompetencies($documentId, $artsId, $artsCompetencies);
        });
    }

    private function ownedExerciseIds(array $subjectIds, array $exerciseIds)
    {
        return DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->whereIn('integrated_themes.subject_id', $subjectIds)
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

    private function correctMvetQuestion(): void
    {
        $row = DB::table('exercises')->where('id', 2970)->first(['content']);
        $content = json_decode($row->content, true, flags: JSON_THROW_ON_ERROR);
        $old = 'Which instrument is associated with the Baka people of Cameroon?';
        $new = 'Which instrument is associated with the Beti-Fang peoples of Cameroon?';
        $current = $content['questions'][3]['question'] ?? null;
        if (! in_array($current, [$old, $new], true)) {
            throw new \RuntimeException('The expected mvet question has changed.');
        }
        $content['questions'][3]['question'] = $new;
        DB::table('exercises')->where('id', 2970)->update([
            'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'updated_at' => now(),
        ]);
    }

    private function upsertCompetencies(int $documentId, int $subjectId, array $competencies): void
    {
        $linkedIds = collect($competencies)->pluck(4)->flatten()->unique()->values();
        if ($linkedIds->isNotEmpty() && $this->ownedExerciseIds([$subjectId], $linkedIds->all())->count() !== $linkedIds->count()) {
            throw new \RuntimeException('A competency link points outside its Class 3 subject.');
        }

        foreach ($competencies as $order => [$code, $name, $description, $pages, $exerciseIds]) {
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

            foreach ($exerciseIds as $exerciseId) {
                DB::table('exercise_school_competency')->updateOrInsert([
                    'exercise_id' => $exerciseId,
                    'school_competency_id' => $competencyId,
                ]);
            }
        }
    }

    private function upsertTheme(int $subjectId, string $slug, string $name, int $order): int
    {
        $id = DB::table('integrated_themes')->where(['subject_id' => $subjectId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => 'Official Class 3 performing arts content.',
            'order' => $order,
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($id) {
            DB::table('integrated_themes')->where('id', $id)->update($values);
            return $id;
        }
        return DB::table('integrated_themes')->insertGetId($values + [
            'subject_id' => $subjectId,
            'slug' => $slug,
            'created_at' => now(),
        ]);
    }

    private function upsertUnit(int $themeId, string $slug, string $name, int $order): int
    {
        $id = DB::table('units')->where(['integrated_theme_id' => $themeId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => "Official Class 3 {$name} content.",
            'summary' => "{$name} practice aligned to the MINEDUB Level II curriculum.",
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

    private function upsertLesson(int $unitId, string $slug, string $name, string $description, int $order): int
    {
        $id = DB::table('lessons')->where(['unit_id' => $unitId, 'slug' => $slug])->value('id');
        $values = [
            'name' => $name,
            'description' => $description,
            'content' => "{$name} practice aligned to the official MINEDUB Level II curriculum.",
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

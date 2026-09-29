<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3FrenchAlignmentPilotSeeder extends Seeder
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
                ->where('subjects.name', 'French')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 3 French was not found.');
            }

            $duplicateGroups = [
                [484, 2141],
                [485, 2142],
                [486, 2143],
                [487, 2144],
                [488, 2145],
                [489, 2146],
                [490, 2147],
                [491, 2148],
                [492, 2149],
                [493, 2150],
                [494, 2151],
            ];
            $allDuplicateIds = collect($duplicateGroups)->flatten()->values();
            $rows = DB::table('exercises')->whereIn('id', $allDuplicateIds)->get()->keyBy('id');
            if ($rows->count() !== $allDuplicateIds->count()) {
                throw new \RuntimeException('The complete Class 3 French duplicate set was not found.');
            }

            $deactivateIds = [];
            foreach ($duplicateGroups as $group) {
                $canonical = $this->canonicalContent($rows[$group[0]]->content);
                foreach (array_slice($group, 1) as $duplicateId) {
                    if ($this->canonicalContent($rows[$duplicateId]->content) !== $canonical) {
                        throw new \RuntimeException("Exercise {$duplicateId} no longer matches its canonical copy.");
                    }
                    $deactivateIds[] = $duplicateId;
                }
            }

            if (DB::table('exercise_attempts')->whereIn('exercise_id', $deactivateIds)->exists()) {
                throw new \RuntimeException('A redundant Class 3 French copy now has attempt history; cleanup stopped.');
            }

            foreach ([489, 2146] as $exerciseId) {
                $content = json_decode($rows[$exerciseId]->content, true, flags: JSON_THROW_ON_ERROR);
                $question = $content['questions'][0] ?? null;
                if (($question['text'] ?? null) !== 'Le passé composé utilise "avoir" ou ___.') {
                    throw new \RuntimeException("Exercise {$exerciseId} no longer contains the expected auxiliary question.");
                }
                if (($question['options'][0] ?? null) !== 'être') {
                    throw new \RuntimeException("Exercise {$exerciseId} no longer offers the expected correct auxiliary.");
                }
                $content['questions'][0]['answer'] = 0;
                DB::table('exercises')->where('id', $exerciseId)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                    'updated_at' => now(),
                ]);
            }

            DB::table('exercises')->whereIn('id', $deactivateIds)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);

            $grammarThemeId = DB::table('integrated_themes')
                ->where('subject_id', $subjectId)
                ->where('name', 'Grammaire')
                ->value('id');
            if (! $grammarThemeId) {
                throw new \RuntimeException('The Class 3 French grammar theme was not found.');
            }

            $verbGroupLessonId = $this->upsertLesson(
                $this->upsertUnit($grammarThemeId, 'groupe-verbal-cod-class-3', 'Groupe verbal et COD', 3),
                'identifier-cod-class-3',
                'Identifier le groupe verbal et le COD',
                1
            );

            $ownedCod = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->where('exercises.id', 494)
                ->where('exercises.is_active', true)
                ->exists();
            if (! $ownedCod) {
                throw new \RuntimeException('The active Class 3 COD exercise was not found.');
            }
            DB::table('exercises')->where('id', 494)->update([
                'lesson_id' => $verbGroupLessonId,
                'updated_at' => now(),
            ]);

            $competencies = [
                [
                    'official_code' => 'MINEDUB-L2-FRE-C3-ORAL',
                    'name' => 'Compréhension et expression orales',
                    'description' => 'Écouter attentivement et s’exprimer de façon compréhensible avec une gestuelle appropriée.',
                    'source_pages' => '29-30, 61-64',
                    'order' => 1,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-FRE-C3-READING',
                    'name' => 'Compréhension écrite et lecture',
                    'description' => 'Lire de courts textes simples avec une prononciation et une intonation correctes, puis relever des informations.',
                    'source_pages' => '29-30, 64-65',
                    'order' => 2,
                    'exercise_ids' => [],
                ],
                [
                    'official_code' => 'MINEDUB-L2-FRE-C3-WRITING-DESCRIPTION',
                    'name' => 'Production écrite et description',
                    'description' => 'Écrire de courts textes cohérents et décrire un lieu, une personne, un animal ou un objet.',
                    'source_pages' => '29-30, 66',
                    'order' => 3,
                    'exercise_ids' => [492, 493],
                ],
                [
                    'official_code' => 'MINEDUB-L2-FRE-C3-GRAMMAR-NOUN-GROUP',
                    'name' => 'Groupe nominal et déterminants',
                    'description' => 'Identifier le groupe nominal et déterminer la nature de ses déterminants.',
                    'source_pages' => '67',
                    'order' => 4,
                    'exercise_ids' => [485],
                ],
                [
                    'official_code' => 'MINEDUB-L2-FRE-C3-GRAMMAR-VERB-GROUP',
                    'name' => 'Groupe verbal et COD',
                    'description' => 'Identifier le groupe verbal et le complément d’objet direct dans une phrase.',
                    'source_pages' => '68',
                    'order' => 5,
                    'exercise_ids' => [494],
                ],
                [
                    'official_code' => 'MINEDUB-L2-FRE-C3-GRAMMAR-ADJECTIVES',
                    'name' => 'Adjectifs qualificatifs et accord',
                    'description' => 'Identifier les adjectifs qualificatifs usuels et les accorder correctement avec les noms.',
                    'source_pages' => '69-70',
                    'order' => 6,
                    'exercise_ids' => [484, 486, 487],
                ],
            ];

            $linkedExerciseIds = collect($competencies)->pluck('exercise_ids')->flatten()->unique()->values();
            $ownedLinkedIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->where('exercises.is_active', true)
                ->whereIn('exercises.id', $linkedExerciseIds)
                ->pluck('exercises.id')
                ->unique();
            if ($ownedLinkedIds->count() !== $linkedExerciseIds->count()) {
                throw new \RuntimeException('The complete Class 3 French competency-link set was not found.');
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
            'description' => 'Grammaire française pour la Class 3.',
            'summary' => 'Groupe verbal et COD selon le programme officiel MINEDUB Level II.',
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
            'description' => 'Identifier le groupe verbal et le complément d’objet direct.',
            'content' => 'Exercices de grammaire française alignés au programme officiel.',
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

    private function canonicalContent(string $content): string
    {
        return json_encode(
            json_decode($content, true, flags: JSON_THROW_ON_ERROR),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );
    }
}

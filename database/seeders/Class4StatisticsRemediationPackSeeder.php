<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4StatisticsRemediationPackSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $level = DB::table('levels')
                ->where('name', 'Class 4')
                ->where('education_subsystem', 'anglophone')
                ->first(['id']);
            $subject = $level
                ? DB::table('subjects')->where('level_id', $level->id)->where('name', 'Mathematics')->first(['id'])
                : null;
            $competency = $subject
                ? DB::table('school_competencies')
                    ->where('subject_id', $subject->id)
                    ->where('name', 'Represent and interpret data on graphs and grids')
                    ->where('verification_status', 'verified_source')
                    ->where('is_active', true)
                    ->first(['id'])
                : null;

            if (! $level || ! $subject || ! $competency) {
                throw new \RuntimeException('The verified Class 4 Statistics competency is unavailable.');
            }

            $exerciseIds = DB::table('exercises')
                ->join('exercise_school_competency', 'exercises.id', '=', 'exercise_school_competency.exercise_id')
                ->where('exercise_school_competency.school_competency_id', $competency->id)
                ->where('exercises.is_active', true)
                ->orderBy('exercises.id')
                ->pluck('exercises.id');

            if ($exerciseIds->count() !== 7) {
                throw new \RuntimeException('Expected exactly 7 verified Class 4 Statistics exercises.');
            }

            $slug = 'class-4-statistics-and-graphs-remediation';
            $values = [
                'name' => 'Statistics and Graphs Practice',
                'description' => 'A focused Class 4 practice path for tallying, ordering data, pictographs, grids and graph interpretation.',
                'type' => 'remediation',
                'visibility' => 'global',
                'target_level_id' => $level->id,
                'target_subject_id' => $subject->id,
                'metadata' => json_encode([
                    'language' => 'en',
                    'name_fr' => 'Renforcement en statistiques et graphiques',
                    'description_fr' => 'Un parcours ciblé de Class 4 sur les tallies, le classement de données, les pictogrammes, les grilles et la lecture de graphiques.',
                    'education_subsystem' => 'anglophone',
                    'source' => 'MINEDUB Level II 2018, page 52',
                    'curriculum_alignment' => 'verified_source',
                    'estimated_minutes' => 28,
                ], JSON_UNESCAPED_SLASHES),
                'is_active' => true,
                'is_published' => true,
                'published_at' => now(),
                'updated_at' => now(),
            ];

            $packId = DB::table('learning_packs')->where('slug', $slug)->value('id');
            if ($packId) {
                DB::table('learning_packs')->where('id', $packId)->update($values);
            } else {
                $packId = DB::table('learning_packs')->insertGetId($values + [
                    'slug' => $slug,
                    'created_at' => now(),
                ]);
            }

            DB::table('learning_pack_exercise')->where('learning_pack_id', $packId)->delete();
            foreach ($exerciseIds as $position => $exerciseId) {
                DB::table('learning_pack_exercise')->insert([
                    'learning_pack_id' => $packId,
                    'exercise_id' => $exerciseId,
                    'position' => $position + 1,
                    'is_required' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}

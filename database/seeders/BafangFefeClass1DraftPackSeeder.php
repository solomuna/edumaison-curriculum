<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BafangFefeClass1DraftPackSeeder extends Seeder
{
    private const LANGUAGE_CODE = 'fmp';

    private const PACK_SLUG = 'fefe-fmp-bafang-class-1-foundations';

    public function run(): void
    {
        DB::transaction(function (): void {
            $now = now();
            $languageId = DB::table('national_languages')
                ->where('code', self::LANGUAGE_CODE)
                ->value('id');
            $subject = DB::table('subjects')
                ->join('levels', 'levels.id', '=', 'subjects.level_id')
                ->where('levels.name', 'Class 1')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'National Languages and Cultures')
                ->first(['subjects.id as subject_id', 'levels.id as level_id']);

            if (! $languageId || ! $subject) {
                throw new \RuntimeException("The fmp catalogue entry or Class 1 National Languages subject is missing.");
            }

            $existingPack = DB::table('learning_packs')
                ->where('slug', self::PACK_SLUG)
                ->first(['id', 'is_published', 'linguistic_review_status']);
            if ($existingPack
                && ((bool) $existingPack->is_published
                    || ! in_array($existingPack->linguistic_review_status, ['draft', 'rejected'], true))) {
                throw new \RuntimeException('A reviewed or published Bafang Class 1 pack already exists; the draft seeder will not overwrite it.');
            }

            $metadata = [
                'language_tag' => self::LANGUAGE_CODE,
                'catalogue_name' => "Fe'fe' / Nufi",
                'family_variant' => 'Bafang',
                'variant_scope' => 'family_declared_pending_speaker_signoff',
                'target' => [
                    'level' => 'Class 1',
                    'subject' => 'National Languages and Cultures',
                ],
                'planned_modules' => [
                    'greetings_and_politeness',
                    'self_and_close_family',
                    'listen_point_and_repeat',
                    'picture_word_matching',
                    'short_classroom_phrases',
                ],
                'contribution_workflow' => [
                    'family_draft',
                    'trusted_speaker_review',
                    'orthography_review',
                    'audio_review',
                    'pedagogical_review',
                    'release_decision',
                ],
                'publication_blockers' => [
                    'family_variant_not_signed_off',
                    'class_1_curriculum_scope_not_signed_off',
                    'orthography_not_signed_off',
                    'vocabulary_not_signed_off',
                    'audio_not_recorded_or_signed_off',
                    'reviewer_identity_not_recorded',
                    'no_exercises_created',
                ],
                'source_policy' => 'reference_only_not_cleared_for_reuse',
                'sources' => [
                    [
                        'role' => 'language_identity_and_bibliography',
                        'url' => 'https://glottolog.org/resource/languoid/id/fefe1239',
                    ],
                    [
                        'role' => 'existing_catalogue_source_record',
                        'url' => 'https://www.silcam.org/resources/archives/5177',
                    ],
                ],
            ];

            $values = [
                'name' => "Fe'fe' / Nufi - Bafang Class 1 foundations",
                'description' => 'Draft pack awaiting Bafang speaker, orthography, audio and pedagogical review.',
                'type' => 'national_language',
                'visibility' => 'global',
                'target_level_id' => (int) $subject->level_id,
                'target_subject_id' => (int) $subject->subject_id,
                'national_language_id' => (int) $languageId,
                'content_version' => '0.1.0-draft',
                'linguistic_review_status' => 'draft',
                'linguistic_reviewed_at' => null,
                'linguistic_review_notes' => 'Not reviewed. Do not publish, activate or assign.',
                'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'is_active' => false,
                'is_published' => false,
                'published_at' => null,
                'deleted_at' => null,
                'updated_at' => $now,
            ];

            if ($existingPack) {
                DB::table('learning_packs')->where('id', $existingPack->id)->update($values);
            } else {
                DB::table('learning_packs')->insert(['slug' => self::PACK_SLUG, 'created_at' => $now] + $values);
            }
        });
    }
}

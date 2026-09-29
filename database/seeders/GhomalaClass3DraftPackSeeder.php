<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GhomalaClass3DraftPackSeeder extends Seeder
{
    private const LANGUAGE_CODE = 'bbj';

    private const PACK_SLUG = 'ghomala-bbj-class-3-foundations';

    public function run(): void
    {
        DB::transaction(function (): void {
            $now = now();

            DB::table('national_languages')->updateOrInsert(
                ['code' => self::LANGUAGE_CODE],
                [
                    'name' => "Ghomala'",
                    'autonym' => 'Ghɔmáláʼ',
                    'aliases' => json_encode([
                        'Ghomala',
                        "Ghomala'",
                        "Ghomálá'",
                        'Ghɔmáláʼ',
                        'Bamendjou',
                    ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                    'source_notes' => implode("\n", [
                        'ISO 639-3: bbj.',
                        'SIL Cameroon archive 83096: Dictionnaire Ghɔmáláꞌ--Français : Français--Ghɔmáláꞌ (2012).',
                        'APROCLAGH language committee: https://www.ghomalaonline.com/',
                        'Literacy resources: https://www.ghomalaonline.com/index.php/fr/alphabetisation',
                        'Bloom language shelf: https://bloomlibrary.org/language:bbj',
                        'Glottolog bibliography: https://glottolog.org/resource/reference/id/469520',
                        'Bamendjou family variant label requires confirmation by a trusted speaker; do not automatically map it to ISO 639-3 nge.',
                        'External resources are references only. No text, illustration or audio has been imported.',
                    ]),
                    'is_selectable' => true,
                    'is_active' => true,
                    'deleted_at' => null,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );

            $languageId = DB::table('national_languages')
                ->where('code', self::LANGUAGE_CODE)
                ->value('id');
            $subject = DB::table('subjects')
                ->join('levels', 'levels.id', '=', 'subjects.level_id')
                ->where('levels.name', 'Class 3')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'National Languages and Cultures')
                ->first(['subjects.id as subject_id', 'levels.id as level_id']);

            if (! $languageId || ! $subject) {
                throw new \RuntimeException('The Ghomala catalogue entry or Class 3 National Languages subject is missing.');
            }

            $existingPack = DB::table('learning_packs')
                ->where('slug', self::PACK_SLUG)
                ->first(['id', 'is_published', 'linguistic_review_status']);
            if ($existingPack
                && ((bool) $existingPack->is_published
                    || ! in_array($existingPack->linguistic_review_status, ['draft', 'rejected'], true))) {
                throw new \RuntimeException('A reviewed or published Ghomala Class 3 pack already exists; the draft seeder will not overwrite it.');
            }

            $metadata = [
                'language_tag' => self::LANGUAGE_CODE,
                'canonical_autonym' => 'Ghɔmáláʼ',
                'dialect_scope' => 'pending_family_selection',
                'curriculum' => [
                    'document_sha256' => '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c',
                    'pages' => '85-88',
                    'level' => 'Class 3',
                    'subject' => 'National Languages and Cultures',
                ],
                'planned_modules' => [
                    'greetings_and_politeness',
                    'nuclear_family_vocabulary',
                    'listening_and_speaking',
                    'short_reading_and_writing',
                    'story_song_and_cultural_context',
                ],
                'publication_blockers' => [
                    'family_variant_not_selected',
                    'orthography_not_signed_off',
                    'vocabulary_not_signed_off',
                    'audio_not_recorded_or_signed_off',
                    'reviewer_identity_not_recorded',
                    'no_exercises_created',
                ],
                'source_policy' => 'reference_only_not_cleared_for_reuse',
                'sources' => [
                    [
                        'role' => 'language_identity_and_dictionary_record',
                        'url' => 'https://www.silcam.org/resources/archives/83096',
                    ],
                    [
                        'role' => 'language_committee_and_literacy_programme',
                        'url' => 'https://www.ghomalaonline.com/',
                    ],
                    [
                        'role' => 'children_literacy_resource_directory',
                        'url' => 'https://www.ghomalaonline.com/index.php/fr/alphabetisation',
                    ],
                    [
                        'role' => 'language_tagged_books_and_audio_directory',
                        'url' => 'https://bloomlibrary.org/language:bbj',
                    ],
                ],
            ];

            $values = [
                'name' => 'Ghɔmáláʼ - Class 3 foundations',
                'description' => 'Draft language pack awaiting family-variant selection and trusted-speaker review.',
                'type' => 'national_language',
                'visibility' => 'global',
                'target_level_id' => (int) $subject->level_id,
                'target_subject_id' => (int) $subject->subject_id,
                'national_language_id' => (int) $languageId,
                'content_version' => '0.1.0-draft',
                'linguistic_review_status' => 'draft',
                'linguistic_reviewed_at' => null,
                'linguistic_review_notes' => 'Not reviewed. Do not publish or assign.',
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

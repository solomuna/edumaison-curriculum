<?php

namespace Database\Seeders;

use App\Models\CurriculumDocument;
use App\Models\Exercise;
use App\Models\Level;
use App\Models\SchoolCompetency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class1LanguageFoundationPilotSeeder extends Seeder
{
    private const DOCUMENT_SHA256 = '38a7c7eddea5ede2bc05bf071716e7067fd9fb987cad2d43fd33a9e294baf957';
    private const DOCUMENT_PATH = 'curricula/cameroon-primary-english-level-1-class-1-2.pdf';

    public function run(): void
    {
        $documentPath = storage_path('app/private/'.self::DOCUMENT_PATH);
        if (! is_file($documentPath) || hash_file('sha256', $documentPath) !== self::DOCUMENT_SHA256) {
            throw new \RuntimeException('The verified Level I curriculum PDF is missing or has an unexpected checksum.');
        }

        DB::transaction(function (): void {
            $document = CurriculumDocument::updateOrCreate(
                ['sha256' => self::DOCUMENT_SHA256],
                [
                    'title' => 'Cameroon Primary School Curriculum - English Subsystem - Level I',
                    'authority' => 'Ministry of Basic Education (MINEDUB)',
                    'education_subsystem' => 'anglophone',
                    'cycle' => 'primary',
                    'levels' => ['Class 1', 'Class 2'],
                    'language' => 'en',
                    'version_label' => '2018',
                    'source_url' => 'https://s3.eu-west-2.amazonaws.com/ebase-bucket/branding/Cameroon-Primary-School-_Level_One.pdf',
                    'storage_path' => self::DOCUMENT_PATH,
                    'verification_status' => 'verified_source',
                    'verified_at' => now(),
                ],
            );

            $level = Level::query()
                ->where('name', 'Class 1')
                ->where('education_subsystem', 'anglophone')
                ->firstOrFail();
            $english = $level->subjects()->where('name', 'English')->firstOrFail();
            $french = $level->subjects()->where('name', 'French')->firstOrFail();

            $competencies = [
                'english_words' => [$english->id, 'Sound and word building', 'Build and spell one-, two- and three-syllable words by manipulating letter sounds.', '41, 47', 1],
                'english_sentences' => [$english->id, 'Writing words and short simple sentences', 'Write familiar words and short subject-verb-object sentences in the correct direction.', '44', 2],
                'french_words' => [$french->id, 'Writing familiar French words and simple sentences', 'Write familiar words and simple French sentences related to everyday themes.', '60', 1],
                'french_mechanics' => [$french->id, 'Capital letters and basic punctuation', 'Identify and apply a capital letter and basic sentence punctuation in short writing.', '60', 2],
                'french_spelling' => [$french->id, 'French phoneme spellings', 'Identify common written forms of French phonemes in familiar words.', '62', 3],
            ];

            $competencyIds = [];
            foreach ($competencies as $key => [$subjectId, $name, $description, $sourcePages, $order]) {
                $competencyIds[$key] = SchoolCompetency::updateOrCreate(
                    ['subject_id' => $subjectId, 'name' => $name],
                    [
                        'curriculum_document_id' => $document->id,
                        'description' => $description,
                        'source_pages' => $sourcePages,
                        'verification_status' => 'verified_source',
                        'order' => $order,
                        'is_active' => true,
                    ],
                )->id;
            }

            $exerciseDefinitions = [
                [
                    'subject' => 'english',
                    'lesson_id' => 374,
                    'title' => 'Dictation: Build and Spell Words',
                    'instructions' => 'Listen to each word and write it carefully.',
                    'category' => 'listening',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 7,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'en-GB',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'cat', 'hint' => 'An animal at home.'],
                            ['text' => 'fish', 'hint' => 'An animal that swims.'],
                            ['text' => 'mother', 'hint' => 'A member of the family.'],
                            ['text' => 'school', 'hint' => 'A place where children learn.'],
                            ['text' => 'banana', 'hint' => 'A yellow fruit.'],
                        ],
                    ],
                    'competencies' => ['english_words'],
                ],
                [
                    'subject' => 'english',
                    'lesson_id' => 10,
                    'title' => 'Dictation: My Home and School',
                    'instructions' => 'Listen and write each short sentence with a capital letter and a full stop.',
                    'category' => 'listening',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 8,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'en-GB',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'My mother is kind.'],
                            ['text' => 'This is my home.'],
                            ['text' => 'We play at school.'],
                        ],
                    ],
                    'competencies' => ['english_sentences'],
                ],
                [
                    'subject' => 'french',
                    'lesson_id' => 79,
                    'title' => 'Dictée : Les mots de la maison',
                    'instructions' => 'Écoute chaque groupe de mots et écris-le soigneusement.',
                    'category' => 'listening',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 7,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'fr-FR',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'la maison'],
                            ['text' => 'la porte'],
                            ['text' => 'le salon'],
                            ['text' => 'la cuisine'],
                        ],
                    ],
                    'competencies' => ['french_words', 'french_spelling'],
                ],
                [
                    'subject' => 'french',
                    'lesson_id' => 82,
                    'title' => 'Dictée : Une phrase à l’école',
                    'instructions' => 'Écoute et écris chaque phrase avec une majuscule et un point.',
                    'category' => 'listening',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 8,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'fr-FR',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'Le sac est sur la table.'],
                            ['text' => 'Je range mon cahier.'],
                            ['text' => 'La classe est propre.'],
                        ],
                    ],
                    'competencies' => ['french_words', 'french_mechanics'],
                ],
            ];

            foreach ($exerciseDefinitions as $definition) {
                $this->assertLessonBelongsToSubject(
                    $definition['lesson_id'],
                    $definition['subject'] === 'french' ? $french->id : $english->id,
                );

                $exercise = Exercise::query()->firstOrNew([
                    'lesson_id' => $definition['lesson_id'],
                    'title' => $definition['title'],
                ]);
                $values = collect($definition)->except(['competencies', 'subject'])->all();
                if ($exercise->exists && $exercise->attempts()->exists()) {
                    foreach ($values as $field => $value) {
                        if ($exercise->{$field} !== $value) {
                            throw new \RuntimeException("Exercise {$exercise->id} has attempts and no longer matches the pilot definition.");
                        }
                    }
                } else {
                    $exercise->fill($values);
                    $exercise->is_active = true;
                    $exercise->save();
                }

                $exercise->schoolCompetencies()->syncWithoutDetaching(
                    collect($definition['competencies'])->map(fn ($key) => $competencyIds[$key])->all(),
                );
            }
        });
    }

    private function assertLessonBelongsToSubject(int $lessonId, int $subjectId): void
    {
        $exists = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->where('lessons.id', $lessonId)
            ->where('integrated_themes.subject_id', $subjectId)
            ->exists();
        if (! $exists) throw new \RuntimeException("Lesson {$lessonId} is not attached to the expected Class 1 subject.");
    }
}

<?php

namespace Database\Seeders;

use App\Models\CurriculumDocument;
use App\Models\Exercise;
use App\Models\Level;
use App\Models\SchoolCompetency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class2LanguageDictationPilotSeeder extends Seeder
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
            $document = CurriculumDocument::query()
                ->where('sha256', self::DOCUMENT_SHA256)
                ->where('verification_status', 'verified_source')
                ->firstOrFail();

            $level = Level::query()
                ->where('name', 'Class 2')
                ->where('education_subsystem', 'anglophone')
                ->firstOrFail();
            $english = $level->subjects()->where('name', 'English')->firstOrFail();
            $french = $level->subjects()->where('name', 'French')->firstOrFail();

            $competencies = [
                'english_sentences' => [
                    $english->id,
                    'Writing short sentences and texts',
                    'Write meaningful short sentences and short texts about familiar people, places and activities.',
                    '44',
                    1,
                ],
                'english_spelling' => [
                    $english->id,
                    'Spelling through sound and word building',
                    'Use familiar sound patterns and word building to spell words in short sentences.',
                    '47',
                    2,
                ],
                'french_sentences' => [
                    $french->id,
                    'Writing simple French sentences and short texts',
                    'Write simple French sentences and short texts of two or three sentences about familiar situations.',
                    '60',
                    1,
                ],
                'french_mechanics' => [
                    $french->id,
                    'Capital letters and punctuation in short texts',
                    'Use capital letters, full stops and commas appropriately in simple French sentences.',
                    '60',
                    2,
                ],
                'french_spelling' => [
                    $french->id,
                    'French phoneme spellings and common homophones',
                    'Apply familiar French phoneme spellings and distinguish common homophones in context.',
                    '62',
                    3,
                ],
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
                    'subject_id' => $english->id,
                    'lesson_id' => 20,
                    'title' => 'Dictation: My Neighbourhood',
                    'instructions' => 'Listen and write each sentence with a capital letter and a full stop.',
                    'category' => 'listening',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 8,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'en-GB',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'My street is clean.', 'audio_url' => '/sounds/lessons/dictation/class2/en/my-street-is-clean.mp3'],
                            ['text' => 'The shop is near my home.', 'audio_url' => '/sounds/lessons/dictation/class2/en/the-shop-is-near-my-home.mp3'],
                            ['text' => 'We walk to school together.', 'audio_url' => '/sounds/lessons/dictation/class2/en/we-walk-to-school-together.mp3'],
                        ],
                    ],
                    'competencies' => ['english_sentences', 'english_spelling'],
                ],
                [
                    'subject_id' => $english->id,
                    'lesson_id' => 74,
                    'title' => 'Dictation: Safe on the Road',
                    'instructions' => 'Listen carefully and write each road-safety sentence.',
                    'category' => 'listening',
                    'difficulty' => 'medium',
                    'estimated_minutes' => 9,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'en-GB',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'I stop before I cross the road.', 'audio_url' => '/sounds/lessons/dictation/class2/en/i-stop-before-i-cross-the-road.mp3'],
                            ['text' => 'I look left and right.', 'audio_url' => '/sounds/lessons/dictation/class2/en/i-look-left-and-right.mp3'],
                            ['text' => 'I use the safe crossing.', 'audio_url' => '/sounds/lessons/dictation/class2/en/i-use-the-safe-crossing.mp3'],
                        ],
                    ],
                    'competencies' => ['english_sentences', 'english_spelling'],
                ],
                [
                    'subject_id' => $french->id,
                    'lesson_id' => 132,
                    'title' => 'Dictée : Noms et phrases',
                    'instructions' => 'Écoute et écris chaque phrase avec les bons accords, une majuscule et un point.',
                    'category' => 'listening',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 8,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'fr-FR',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'La fille porte un sac.', 'audio_url' => '/sounds/lessons/dictation/class2/fr/la-fille-porte-un-sac.mp3'],
                            ['text' => 'Les garçons jouent dans la cour.', 'audio_url' => '/sounds/lessons/dictation/class2/fr/les-garcons-jouent-dans-la-cour.mp3'],
                            ['text' => 'Cette maison est grande.', 'audio_url' => '/sounds/lessons/dictation/class2/fr/cette-maison-est-grande.mp3'],
                        ],
                    ],
                    'competencies' => ['french_sentences', 'french_mechanics', 'french_spelling'],
                ],
                [
                    'subject_id' => $french->id,
                    'lesson_id' => 133,
                    'title' => 'Dictée : Nos actions',
                    'instructions' => 'Écoute puis écris chaque phrase au présent avec sa ponctuation.',
                    'category' => 'listening',
                    'difficulty' => 'medium',
                    'estimated_minutes' => 9,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'fr-FR',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'Nous allons à l’école.', 'audio_url' => '/sounds/lessons/dictation/class2/fr/nous-allons-a-lecole.mp3'],
                            ['text' => 'Elle range ses cahiers.', 'audio_url' => '/sounds/lessons/dictation/class2/fr/elle-range-ses-cahiers.mp3'],
                            ['text' => 'Ils jouent dans la cour.', 'audio_url' => '/sounds/lessons/dictation/class2/fr/ils-jouent-dans-la-cour.mp3'],
                        ],
                    ],
                    'competencies' => ['french_sentences', 'french_mechanics', 'french_spelling'],
                ],
            ];

            foreach ($exerciseDefinitions as $definition) {
                $this->assertLessonBelongsToSubject($definition['lesson_id'], $definition['subject_id']);

                $exercise = Exercise::query()->firstOrNew([
                    'lesson_id' => $definition['lesson_id'],
                    'title' => $definition['title'],
                ]);
                $values = collect($definition)->except(['competencies', 'subject_id'])->all();

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
                    collect($definition['competencies'])
                        ->map(fn (string $key): int => $competencyIds[$key])
                        ->all(),
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

        if (! $exists) {
            throw new \RuntimeException("Lesson {$lessonId} is not attached to the expected Class 2 subject.");
        }
    }
}

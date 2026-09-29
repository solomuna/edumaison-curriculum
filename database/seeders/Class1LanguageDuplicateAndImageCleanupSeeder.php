<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class1LanguageDuplicateAndImageCleanupSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $duplicateGroups = [
                [206, 289, 372],
                [201, 284, 367],
                [197, 280, 363],
                [198, 281, 364],
                [366, 200, 283],
                [202, 285, 368],
                [199, 282, 365],
                [369, 203, 286],
            ];
            $allIds = collect($duplicateGroups)->flatten()->unique()->values();
            $rows = DB::table('exercises')->whereIn('id', $allIds)->get()->keyBy('id');

            if ($rows->count() !== $allIds->count()) {
                throw new \RuntimeException('The complete Class 1 language duplicate set was not found.');
            }
            if ($this->ownedEnglishClass1Ids($allIds->all())->count() !== $allIds->count()) {
                throw new \RuntimeException('A duplicate candidate no longer belongs to English Class 1.');
            }

            $deactivateIds = [];
            foreach ($duplicateGroups as $group) {
                $canonical = $this->comparableContent($rows[$group[0]]->content);
                foreach (array_slice($group, 1) as $duplicateId) {
                    if ($this->comparableContent($rows[$duplicateId]->content) !== $canonical) {
                        throw new \RuntimeException("Exercise {$duplicateId} is no longer a pedagogical copy of {$group[0]}.");
                    }
                    $deactivateIds[] = $duplicateId;
                }
            }

            if (DB::table('exercise_school_competency')->whereIn('exercise_id', $deactivateIds)->exists()) {
                throw new \RuntimeException('A redundant copy is now linked to a school competency.');
            }
            if (DB::table('learning_pack_exercise')->whereIn('exercise_id', $deactivateIds)->exists()) {
                throw new \RuntimeException('A redundant copy is now linked to a learning pack.');
            }
            if (DB::table('exercise_media_assets')->whereIn('exercise_id', $deactivateIds)->exists()) {
                throw new \RuntimeException('A redundant copy is now linked to a media asset.');
            }

            DB::table('exercises')
                ->whereIn('id', $deactivateIds)
                ->where('is_active', true)
                ->update(['is_active' => false, 'updated_at' => now()]);

            $misleadingImages = [
                9 => '/storage/images/edu/flower.jpg',
                11 => '/storage/images/edu/family.png',
                13 => '/storage/images/edu/tomato.jpg',
                198 => '/storage/images/edu/family.png',
                202 => '/storage/images/edu/ear.jpg',
                2560 => '/storage/images/edu/hand.jpg',
                2561 => '/storage/images/edu/hand.jpg',
                2562 => '/storage/images/edu/hand.jpg',
                2565 => '/storage/images/edu/hand.jpg',
                2566 => '/storage/images/edu/hand.jpg',
                2567 => '/storage/images/edu/hand.jpg',
                2568 => '/storage/images/edu/hand.jpg',
                2569 => '/storage/images/edu/hand.jpg',
            ];
            if ($this->ownedEnglishClass1Ids(array_keys($misleadingImages))->count() !== count($misleadingImages)) {
                throw new \RuntimeException('A misleading-image exercise no longer belongs to English Class 1.');
            }

            foreach ($misleadingImages as $exerciseId => $expectedPath) {
                $content = json_decode(
                    DB::table('exercises')->where('id', $exerciseId)->value('content'),
                    true,
                    flags: JSON_THROW_ON_ERROR
                );
                if (! array_key_exists('image_url', $content)) {
                    continue;
                }
                if ($content['image_url'] !== $expectedPath) {
                    throw new \RuntimeException("Exercise {$exerciseId} no longer uses the expected misleading image.");
                }

                unset($content['image_url']);
                DB::table('exercises')->where('id', $exerciseId)->update([
                    'content' => json_encode(
                        $content,
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
                    ),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    private function ownedEnglishClass1Ids(array $exerciseIds)
    {
        return DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->join('subjects', 'integrated_themes.subject_id', '=', 'subjects.id')
            ->join('levels', 'subjects.level_id', '=', 'levels.id')
            ->where('levels.name', 'Class 1')
            ->where('levels.education_subsystem', 'anglophone')
            ->where('subjects.name', 'English')
            ->whereNull('exercises.deleted_at')
            ->whereIn('exercises.id', $exerciseIds)
            ->pluck('exercises.id')
            ->unique();
    }

    private function comparableContent(string $json): string
    {
        $content = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        $this->removePresentationOnlyKeys($content);
        $this->sortKeysRecursively($content);

        return json_encode(
            $content,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );
    }

    private function removePresentationOnlyKeys(array &$value): void
    {
        unset($value['explanation'], $value['illustration'], $value['image_url']);
        foreach ($value as &$item) {
            if (is_array($item)) {
                $this->removePresentationOnlyKeys($item);
            }
        }
    }

    private function sortKeysRecursively(array &$value): void
    {
        foreach ($value as &$item) {
            if (is_array($item)) {
                $this->sortKeysRecursively($item);
            }
        }
        if (! array_is_list($value)) {
            ksort($value);
        }
    }
}

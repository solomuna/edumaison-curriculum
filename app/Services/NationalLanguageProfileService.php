<?php

namespace App\Services;

use App\Models\LearningPack;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NationalLanguageProfileService
{
    public function forChild(int $childId, ?int $householdId = null): array
    {
        $context = $this->childContext($childId, $householdId);
        $selections = $this->languageSelectionsForChild($context);

        if ($selections === []) {
            $current = $this->profileForSelection($this->legacySelection($context));
            $languages = $current['display_name'] === null ? [] : [$current];
        } else {
            $languages = array_map(fn (array $selection): array => $this->profileForSelection($selection), $selections);
            $current = collect($languages)->firstWhere('is_current', true) ?? $languages[0];
        }

        return $current + ['languages' => array_values($languages), 'can_choose' => count($languages) > 1];
    }

    public function selectionForChild(int $childId, ?int $householdId = null): array
    {
        $context = $this->childContext($childId, $householdId);
        $selections = $this->languageSelectionsForChild($context);
        return $selections === []
            ? $this->legacySelection($context)
            : (collect($selections)->firstWhere('is_current', true) ?? $selections[0]);
    }

    public function householdLanguages(int $householdId): array
    {
        return DB::table('household_national_language as family_language')
            ->leftJoin('national_languages as language', 'language.id', '=', 'family_language.national_language_id')
            ->where('family_language.household_id', $householdId)
            ->where('family_language.is_active', true)
            ->orderBy('family_language.priority')
            ->orderBy('family_language.id')
            ->get([
                'family_language.id as language_profile_id', 'family_language.national_language_id as language_id',
                'family_language.custom_name', 'family_language.family_label', 'family_language.variant_name',
                'family_language.priority', 'language.code', 'language.name', 'language.autonym',
            ])
            ->map(fn (object $row): array => $this->selectionFromRow($row, 'household'))
            ->values()->all();
    }

    public function replaceHouseholdLanguages(
        int $householdId,
        array $languageIds,
        array $customNames = [],
        array $variantNames = []
    ): void
    {
        $languageIds = collect($languageIds)->map(fn ($id): int => (int) $id)->filter()->unique()->values();
        $customNames = collect($customNames)
            ->map(fn ($name): ?string => $this->cleanName($name))
            ->filter()
            ->unique(fn (string $name): string => Str::lower($name))
            ->values();

        DB::transaction(function () use ($householdId, $languageIds, $customNames, $variantNames): void {
            $now = now();
            DB::table('household_national_language')->where('household_id', $householdId)
                ->update(['is_active' => false, 'updated_at' => $now]);

            $priority = 0;
            foreach ($languageIds as $languageId) {
                $variantName = $this->cleanName($variantNames[$languageId] ?? $variantNames[(string) $languageId] ?? null);
                DB::table('household_national_language')->updateOrInsert(
                    ['household_id' => $householdId, 'national_language_id' => $languageId],
                    ['custom_name' => null, 'variant_name' => $variantName, 'priority' => $priority++, 'is_active' => true, 'updated_at' => $now]
                );
            }
            foreach ($customNames as $customName) {
                $profileId = DB::table('household_national_language')->where('household_id', $householdId)
                    ->whereNull('national_language_id')->where('custom_name', $customName)->value('id');
                $values = ['priority' => $priority++, 'is_active' => true, 'updated_at' => $now];
                if ($profileId) {
                    DB::table('household_national_language')->where('id', $profileId)->update($values);
                } else {
                    DB::table('household_national_language')->insert([
                        'household_id' => $householdId, 'national_language_id' => null,
                        'custom_name' => $customName, 'created_at' => $now,
                    ] + $values);
                }
            }

            $activeProfileIds = DB::table('household_national_language')->where('household_id', $householdId)
                ->where('is_active', true)->orderBy('priority')->pluck('id')->map(fn ($id): int => (int) $id)->all();
            $allProfileIds = DB::table('household_national_language')->where('household_id', $householdId)->pluck('id');
            $children = DB::table('children')->where('household_id', $householdId)->where('is_active', true)->pluck('id');

            foreach ($children as $childId) {
                if ($allProfileIds->isNotEmpty()) {
                    DB::table('child_national_language')->where('child_id', $childId)
                        ->whereIn('household_national_language_id', $allProfileIds)
                        ->whereNotIn('household_national_language_id', $activeProfileIds ?: [-1])
                        ->update(['is_enabled' => false, 'is_current' => false, 'updated_at' => $now]);
                }
                foreach ($activeProfileIds as $position => $profileId) {
                    $exists = DB::table('child_national_language')->where('child_id', $childId)
                        ->where('household_national_language_id', $profileId)->exists();
                    if (! $exists) {
                        DB::table('child_national_language')->insert([
                            'child_id' => $childId, 'household_national_language_id' => $profileId,
                            'position' => $position, 'is_enabled' => true, 'is_current' => false,
                            'created_at' => $now, 'updated_at' => $now,
                        ]);
                    }
                }
                $this->normalizeCurrentChoice((int) $childId, $activeProfileIds);
            }

            $first = DB::table('household_national_language')->where('household_id', $householdId)
                ->where('is_active', true)->orderBy('priority')->first(['national_language_id', 'custom_name']);
            DB::table('households')->where('id', $householdId)->update([
                'national_language_id' => $first?->national_language_id,
                'national_language_other_name' => $first?->national_language_id ? null : $first?->custom_name,
                'updated_at' => $now,
            ]);
        });
    }

    public function replaceChildLanguages(int $childId, int $householdId, array $profileIds, ?int $currentProfileId = null): void
    {
        $profileIds = collect($profileIds)->map(fn ($id): int => (int) $id)->filter()->unique()->values()->all();
        $allowedIds = DB::table('household_national_language')->where('household_id', $householdId)
            ->where('is_active', true)->whereIn('id', $profileIds ?: [-1])->pluck('id')
            ->map(fn ($id): int => (int) $id)->all();
        if (collect($allowedIds)->sort()->values()->all() !== collect($profileIds)->sort()->values()->all()) {
            throw ValidationException::withMessages(['national_language_profile_ids' => 'Une langue choisie n’est pas active pour cette famille.']);
        }
        if ($allowedIds === []) {
            throw ValidationException::withMessages(['national_language_profile_ids' => 'Choisissez au moins une langue familiale.']);
        }
        $currentProfileId = in_array((int) $currentProfileId, $allowedIds, true) ? (int) $currentProfileId : $allowedIds[0];

        DB::transaction(function () use ($childId, $householdId, $allowedIds, $currentProfileId): void {
            $now = now();
            $familyIds = DB::table('household_national_language')->where('household_id', $householdId)->pluck('id');
            DB::table('child_national_language')->where('child_id', $childId)
                ->whereIn('household_national_language_id', $familyIds)
                ->update(['is_enabled' => false, 'is_current' => false, 'updated_at' => $now]);
            foreach ($allowedIds as $position => $profileId) {
                DB::table('child_national_language')->updateOrInsert(
                    ['child_id' => $childId, 'household_national_language_id' => $profileId],
                    ['position' => $position, 'is_enabled' => true, 'is_current' => $profileId === $currentProfileId,
                        'selected_at' => $profileId === $currentProfileId ? $now : null, 'updated_at' => $now]
                );
            }
            $selected = DB::table('household_national_language')->where('id', $currentProfileId)
                ->first(['national_language_id', 'custom_name']);
            DB::table('children')->where('id', $childId)->where('household_id', $householdId)->update([
                'national_language_id' => $selected?->national_language_id,
                'national_language_other_name' => $selected?->national_language_id ? null : $selected?->custom_name,
                'updated_at' => $now,
            ]);
        });
    }

    public function selectCurrentLanguage(int $childId, int $householdId, int $profileId): array
    {
        $choice = DB::table('child_national_language as child_language')
            ->join('household_national_language as family_language', 'family_language.id', '=', 'child_language.household_national_language_id')
            ->where('child_language.child_id', $childId)->where('child_language.household_national_language_id', $profileId)
            ->where('child_language.is_enabled', true)->where('family_language.household_id', $householdId)
            ->where('family_language.is_active', true)->first(['family_language.national_language_id', 'family_language.custom_name']);
        if (! $choice) {
            throw ValidationException::withMessages(['household_language_id' => 'Cette langue n’est pas disponible pour cet enfant.']);
        }

        DB::transaction(function () use ($childId, $householdId, $profileId, $choice): void {
            $now = now();
            DB::table('child_national_language')->where('child_id', $childId)
                ->update(['is_current' => false, 'updated_at' => $now]);
            DB::table('child_national_language')->where('child_id', $childId)
                ->where('household_national_language_id', $profileId)
                ->update(['is_current' => true, 'selected_at' => $now, 'updated_at' => $now]);
            DB::table('children')->where('id', $childId)->where('household_id', $householdId)->update([
                'national_language_id' => $choice->national_language_id,
                'national_language_other_name' => $choice->national_language_id ? null : $choice->custom_name,
                'updated_at' => $now,
            ]);
        });
        return $this->forChild($childId, $householdId);
    }

    public function packMatchesChild(LearningPack $pack, int $childId, int $householdId): bool
    {
        return $pack->type !== 'national_language'
            || $this->packMatchesSelection($pack, $this->selectionForChild($childId, $householdId));
    }

    public function packMatchesAnyChildLanguage(LearningPack $pack, int $childId, int $householdId): bool
    {
        if ($pack->type !== 'national_language') return true;
        $context = $this->childContext($childId, $householdId);
        $selections = $this->languageSelectionsForChild($context) ?: [$this->legacySelection($context)];
        return collect($selections)->contains(fn (array $selection): bool => $this->packMatchesSelection($pack, $selection));
    }

    public function householdCanActivatePack(LearningPack $pack, int $householdId): bool
    {
        if ($pack->type !== 'national_language') return true;
        return DB::table('children')->where('household_id', $householdId)->where('is_active', true)->pluck('id')
            ->contains(fn ($childId): bool => $this->packMatchesAnyChildLanguage($pack, (int) $childId, $householdId));
    }

    public function restrictExerciseQuery(EloquentBuilder|QueryBuilder $query, int $childId, int $householdId, string $exerciseTable = 'exercises'): void
    {
        $eligiblePackIds = $this->eligibleAssignedPackIds($childId, $householdId);
        $query->where(function ($outer) use ($eligiblePackIds, $exerciseTable): void {
            $outer->whereNotExists(function (QueryBuilder $subquery) use ($exerciseTable): void {
                $subquery->selectRaw('1')->from('learning_pack_exercise as language_pack_link')
                    ->join('learning_packs as language_pack', 'language_pack.id', '=', 'language_pack_link.learning_pack_id')
                    ->whereColumn('language_pack_link.exercise_id', $exerciseTable.'.id')
                    ->where('language_pack.type', 'national_language');
            });
            if ($eligiblePackIds !== []) {
                $outer->orWhereExists(function (QueryBuilder $subquery) use ($eligiblePackIds, $exerciseTable): void {
                    $subquery->selectRaw('1')->from('learning_pack_exercise as eligible_language_link')
                        ->whereColumn('eligible_language_link.exercise_id', $exerciseTable.'.id')
                        ->whereIn('eligible_language_link.learning_pack_id', $eligiblePackIds);
                });
            }
        });
    }

    public function assertExerciseAvailable(int $exerciseId, int $childId, int $householdId): void
    {
        $languagePackIds = DB::table('learning_pack_exercise')
            ->join('learning_packs', 'learning_packs.id', '=', 'learning_pack_exercise.learning_pack_id')
            ->where('learning_pack_exercise.exercise_id', $exerciseId)->where('learning_packs.type', 'national_language')
            ->pluck('learning_packs.id')->map(fn ($id): int => (int) $id)->all();
        if ($languagePackIds !== [] && array_intersect($languagePackIds, $this->eligibleAssignedPackIds($childId, $householdId)) === []) {
            throw ValidationException::withMessages(['exercise_id' => 'This national-language activity is not available for this child.']);
        }
    }

    private function childContext(int $childId, ?int $householdId): object
    {
        $row = DB::table('children')->join('households', 'households.id', '=', 'children.household_id')
            ->leftJoin('national_languages as child_language', 'child_language.id', '=', 'children.national_language_id')
            ->leftJoin('national_languages as household_language', 'household_language.id', '=', 'households.national_language_id')
            ->where('children.id', $childId)
            ->when($householdId, fn (QueryBuilder $query) => $query->where('children.household_id', $householdId))
            ->first([
                'children.id as child_id', 'children.household_id', 'children.level_id',
                'children.national_language_id as child_language_id', 'children.national_language_other_name as child_other_name',
                'households.national_language_id as household_language_id', 'households.national_language_other_name as household_other_name',
                'child_language.code as child_language_code', 'child_language.name as child_language_name',
                'child_language.autonym as child_language_autonym', 'household_language.code as household_language_code',
                'household_language.name as household_language_name', 'household_language.autonym as household_language_autonym',
            ]);
        if (! $row) {
            throw ValidationException::withMessages(['child_id' => 'This child does not belong to the current family.']);
        }
        return $row;
    }

    private function languageSelectionsForChild(object $context): array
    {
        $hasChildChoices = DB::table('child_national_language')->where('child_id', $context->child_id)->exists();
        return DB::table('household_national_language as family_language')
            ->leftJoin('national_languages as language', 'language.id', '=', 'family_language.national_language_id')
            ->leftJoin('child_national_language as child_language', function ($join) use ($context): void {
                $join->on('child_language.household_national_language_id', '=', 'family_language.id')
                    ->where('child_language.child_id', '=', $context->child_id);
            })
            ->where('family_language.household_id', $context->household_id)->where('family_language.is_active', true)
            ->when($hasChildChoices, fn (QueryBuilder $query) => $query->where('child_language.is_enabled', true))
            ->orderByDesc('child_language.is_current')->orderByRaw('COALESCE(child_language.position, family_language.priority)')
            ->orderBy('family_language.id')
            ->get([
                'family_language.id as language_profile_id', 'family_language.national_language_id as language_id',
                'family_language.custom_name', 'family_language.family_label', 'family_language.variant_name',
                'language.code', 'language.name', 'language.autonym', 'child_language.is_current',
            ])->map(function (object $row) use ($context, $hasChildChoices): array {
                return $this->selectionFromRow($row, $hasChildChoices ? 'child' : 'household') + [
                    'child_id' => (int) $context->child_id, 'household_id' => (int) $context->household_id,
                    'level_id' => (int) $context->level_id, 'is_current' => (bool) ($row->is_current ?? false),
                ];
            })->values()->all();
    }

    private function selectionFromRow(object $row, string $source): array
    {
        $customName = $this->cleanName($row->custom_name ?? null);
        $familyLabel = $this->cleanName($row->family_label ?? null);
        $name = $row->name ?? null;
        return [
            'language_profile_id' => (int) $row->language_profile_id, 'source' => $source,
            'language_id' => $row->language_id ? (int) $row->language_id : null,
            'code' => $row->code ?? null, 'name' => $name, 'autonym' => $row->autonym ?? null,
            'custom_name' => $customName, 'family_label' => $familyLabel,
            'variant_name' => $this->cleanName($row->variant_name ?? null),
            'display_name' => $familyLabel ?: ($this->cleanName($row->variant_name ?? null) ?: ($name ?: $customName)),
        ];
    }

    private function legacySelection(object $row): array
    {
        $childOther = $this->cleanName($row->child_other_name);
        $householdOther = $this->cleanName($row->household_other_name);
        $usesChild = $row->child_language_id !== null || $childOther !== null;
        return [
            'child_id' => (int) $row->child_id, 'household_id' => (int) $row->household_id,
            'level_id' => (int) $row->level_id, 'language_profile_id' => null,
            'source' => $usesChild ? 'child' : ($row->household_language_id !== null || $householdOther !== null ? 'household' : 'none'),
            'language_id' => $usesChild ? ($row->child_language_id ? (int) $row->child_language_id : null) : ($row->household_language_id ? (int) $row->household_language_id : null),
            'code' => $usesChild ? $row->child_language_code : $row->household_language_code,
            'name' => $usesChild ? $row->child_language_name : $row->household_language_name,
            'autonym' => $usesChild ? $row->child_language_autonym : $row->household_language_autonym,
            'custom_name' => $usesChild ? $childOther : $householdOther,
            'family_label' => null, 'variant_name' => null,
            'display_name' => $usesChild ? ($row->child_language_name ?: $childOther) : ($row->household_language_name ?: $householdOther),
            'is_current' => true,
        ];
    }

    private function profileForSelection(array $selection): array
    {
        $pack = $selection['language_id'] ? $this->verifiedPack($selection) : null;
        $status = match (true) {
            $selection['display_name'] === null => 'not_configured',
            $selection['language_id'] === null => 'awaiting_catalogue',
            $pack === null => 'awaiting_pack',
            default => 'ready',
        };
        return $selection + ['status' => $status, 'pack' => $pack ? [
            'id' => $pack->id, 'name' => $pack->name, 'content_version' => $pack->content_version,
            'published_at' => $pack->published_at?->toIso8601String(),
        ] : null];
    }

    private function verifiedPack(array $selection): ?LearningPack
    {
        return LearningPack::query()->where('type', 'national_language')->where('national_language_id', $selection['language_id'])
            ->where('linguistic_review_status', 'verified')->whereNotNull('content_version')->whereNotNull('linguistic_reviewed_at')
            ->where('is_active', true)->where('is_published', true)
            ->where(fn (EloquentBuilder $query) => $query->whereNull('target_level_id')->orWhere('target_level_id', $selection['level_id']))
            ->whereHas('targetSubject', fn (EloquentBuilder $query) => $query->where('name', 'National Languages and Cultures'))
            ->orderByDesc('published_at')->orderByDesc('id')->first();
    }

    private function packMatchesSelection(LearningPack $pack, array $selection): bool
    {
        if (! $pack->is_active || ! $pack->is_published || $pack->linguistic_review_status !== 'verified'
            || ! $pack->content_version || ! $pack->linguistic_reviewed_at || ! $pack->national_language_id
            || (int) $pack->national_language_id !== ($selection['language_id'] ?? null)
            || ($pack->target_level_id && (int) $pack->target_level_id !== $selection['level_id'])) return false;
        return $pack->targetSubject()->where('name', 'National Languages and Cultures')->exists();
    }

    private function normalizeCurrentChoice(int $childId, array $activeProfileIds): void
    {
        if ($activeProfileIds === []) return;
        $current = DB::table('child_national_language')->where('child_id', $childId)->where('is_enabled', true)
            ->where('is_current', true)->whereIn('household_national_language_id', $activeProfileIds)
            ->value('household_national_language_id');
        if (! $current) {
            $current = DB::table('child_national_language')->where('child_id', $childId)->where('is_enabled', true)
                ->whereIn('household_national_language_id', $activeProfileIds)
                ->orderBy('position')->value('household_national_language_id');
        }
        $current = $current ? (int) $current : $activeProfileIds[0];
        DB::table('child_national_language')->where('child_id', $childId)->update(['is_current' => false]);
        DB::table('child_national_language')->where('child_id', $childId)->where('household_national_language_id', $current)
            ->update(['is_enabled' => true, 'is_current' => true, 'selected_at' => now(), 'updated_at' => now()]);
    }

    private function eligibleAssignedPackIds(int $childId, int $householdId): array
    {
        $selection = $this->selectionForChild($childId, $householdId);
        if (! $selection['language_id']) return [];
        return DB::table('child_learning_pack')->join('household_learning_pack', function ($join): void {
            $join->on('household_learning_pack.household_id', '=', 'child_learning_pack.household_id')
                ->on('household_learning_pack.learning_pack_id', '=', 'child_learning_pack.learning_pack_id');
        })->join('learning_packs', 'learning_packs.id', '=', 'child_learning_pack.learning_pack_id')
            ->join('subjects', 'subjects.id', '=', 'learning_packs.target_subject_id')
            ->where('child_learning_pack.household_id', $householdId)->where('child_learning_pack.child_id', $childId)
            ->whereIn('child_learning_pack.status', ['assigned', 'in_progress', 'completed'])
            ->where('household_learning_pack.is_active', true)->where('learning_packs.type', 'national_language')
            ->where('learning_packs.national_language_id', $selection['language_id'])
            ->where('learning_packs.linguistic_review_status', 'verified')->whereNotNull('learning_packs.content_version')
            ->whereNotNull('learning_packs.linguistic_reviewed_at')->where('learning_packs.is_active', true)
            ->where('learning_packs.is_published', true)
            ->where(fn (QueryBuilder $query) => $query->whereNull('learning_packs.target_level_id')->orWhere('learning_packs.target_level_id', $selection['level_id']))
            ->where('subjects.name', 'National Languages and Cultures')->pluck('learning_packs.id')
            ->map(fn ($id): int => (int) $id)->unique()->values()->all();
    }

    private function cleanName(?string $name): ?string
    {
        $value = trim((string) $name);
        return $value !== '' ? $value : null;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChildLearningPack;
use App\Models\ExerciseAttempt;
use App\Models\HouseholdLearningPack;
use App\Models\LearningPack;
use App\Support\FamilyContext;
use App\Services\NationalLanguageProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LearningPackController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $householdId = $this->householdId($request);
        $activations = HouseholdLearningPack::query()
            ->where('household_id', $householdId)
            ->get()
            ->keyBy('learning_pack_id');
        $activeAssignments = ChildLearningPack::query()
            ->where('household_id', $householdId)
            ->whereIn('status', ['assigned', 'in_progress', 'completed'])
            ->get(['learning_pack_id', 'child_id'])
            ->groupBy('learning_pack_id');

        $packs = LearningPack::query()
            ->where('visibility', 'global')
            ->where('is_active', true)
            ->where('is_published', true)
            ->with(['targetLevel:id,name,slug', 'targetSubject:id,name,slug', 'nationalLanguage:id,code,name,autonym'])
            ->withCount('exercises')
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->map(function (LearningPack $pack) use ($activations, $activeAssignments): array {
                $activation = $activations->get($pack->id);
                $assignments = $activeAssignments->get($pack->id, collect());

                return [
                    'id' => $pack->id,
                    'name' => $pack->name,
                    'slug' => $pack->slug,
                    'description' => $pack->description,
                    'type' => $pack->type,
                    'target_level' => $pack->targetLevel,
                    'target_subject' => $pack->targetSubject,
                    'national_language' => $pack->nationalLanguage,
                    'content_version' => $pack->content_version,
                    'linguistic_review_status' => $pack->linguistic_review_status,
                    'exercise_count' => $pack->exercises_count,
                    'is_enabled_for_family' => (bool) ($activation?->is_active ?? false),
                    'assigned_children_count' => $assignments->count(),
                    'assigned_child_ids' => $assignments->pluck('child_id')->map(fn ($id) => (int) $id)->values(),
                    'metadata' => $pack->metadata,
                ];
            });

        return response()->json(['packs' => $packs]);
    }

    public function activate(Request $request, LearningPack $learningPack): JsonResponse
    {
        $this->ensureAvailable($learningPack);
        $householdId = $this->householdId($request);
        abort_unless(
            app(NationalLanguageProfileService::class)->householdCanActivatePack($learningPack, $householdId),
            422,
            'Ce pack ne correspond à la langue nationale d’aucun enfant actif de la famille.'
        );

        $activation = HouseholdLearningPack::query()->updateOrCreate(
            ['household_id' => $householdId, 'learning_pack_id' => $learningPack->id],
            ['activated_by_user_id' => $request->user()?->id, 'is_active' => true]
        );

        return response()->json(['ok' => true, 'activation' => $activation]);
    }

    public function deactivate(Request $request, LearningPack $learningPack): JsonResponse
    {
        $householdId = $this->householdId($request);
        DB::transaction(function () use ($householdId, $learningPack): void {
            HouseholdLearningPack::query()
                ->where('household_id', $householdId)
                ->where('learning_pack_id', $learningPack->id)
                ->update(['is_active' => false, 'updated_at' => now()]);

            ChildLearningPack::query()
                ->where('household_id', $householdId)
                ->where('learning_pack_id', $learningPack->id)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->update(['status' => 'paused', 'updated_at' => now()]);
        });

        return response()->json(['ok' => true]);
    }

    public function forChild(Request $request, int $childId): JsonResponse
    {
        $householdId = $this->householdId($request);
        $schoolYearId = DB::table('school_years')->where('is_current', true)->value('id');
        $activePackIds = HouseholdLearningPack::query()
            ->where('household_id', $householdId)
            ->where('is_active', true)
            ->pluck('learning_pack_id');

        $assignments = ChildLearningPack::query()
            ->where('household_id', $householdId)
            ->where('child_id', $childId)
            ->whereIn('learning_pack_id', $activePackIds)
            ->whereIn('status', ['assigned', 'in_progress', 'completed'])
            ->whereHas('learningPack', fn ($query) => $query
                ->where('visibility', 'global')
                ->where('is_active', true)
                ->where('is_published', true))
            ->with([
                'learningPack.targetLevel:id,name,slug',
                'learningPack.targetSubject:id,name,slug',
                'learningPack.nationalLanguage:id,code,name,autonym',
                'learningPack.exercises' => fn ($query) => $query
                    ->where('exercises.is_active', true)
                    ->with('lesson.unit.integratedTheme.subject'),
            ])
            ->orderByDesc('updated_at')
            ->get();

        $profiles = app(NationalLanguageProfileService::class);
        $assignments = $assignments
            ->filter(fn (ChildLearningPack $assignment): bool => $profiles->packMatchesChild(
                $assignment->learningPack,
                $childId,
                $householdId
            ))
            ->values();

        $packs = $assignments->map(function (ChildLearningPack $assignment) use ($childId, $schoolYearId): array {
            $pack = $assignment->learningPack;
            $exercises = $pack->exercises->map(fn ($exercise) => $this->exercisePayload($exercise))->values();
            $exerciseIds = $exercises->pluck('id');
            $completedIds = ExerciseAttempt::query()
                ->where('child_id', $childId)
                ->whereIn('exercise_id', $exerciseIds)
                ->when($schoolYearId, fn ($query) => $query->where('school_year_id', $schoolYearId))
                ->where('attempted_at', '>=', $assignment->created_at)
                ->pluck('exercise_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            return [
                'id' => $pack->id,
                'name' => $pack->name,
                'description' => $pack->description,
                'type' => $pack->type,
                'status' => $assignment->status,
                'target_level' => $pack->targetLevel,
                'target_subject' => $pack->targetSubject,
                'national_language' => $pack->nationalLanguage,
                'content_version' => $pack->content_version,
                'linguistic_review_status' => $pack->linguistic_review_status,
                'metadata' => $pack->metadata,
                'exercise_count' => $exercises->count(),
                'completed_count' => $completedIds->count(),
                'completed_exercise_ids' => $completedIds,
                'exercises' => $exercises,
            ];
        })->values();

        return response()->json(['packs' => $packs]);
    }

    public function assign(Request $request, int $childId, LearningPack $learningPack): JsonResponse
    {
        $this->ensureAvailable($learningPack);
        $householdId = $this->householdId($request);
        abort_unless(
            app(NationalLanguageProfileService::class)->packMatchesAnyChildLanguage($learningPack, $childId, $householdId),
            422,
            'Ce pack ne correspond pas à la langue nationale de cet enfant ou n’a pas été validé.'
        );
        if ($learningPack->target_level_id) {
            $childLevelId = DB::table('children')
                ->where('household_id', $householdId)
                ->where('id', $childId)
                ->value('level_id');
            abort_unless(
                (int) $childLevelId === (int) $learningPack->target_level_id,
                422,
                'Ce parcours ne correspond pas au niveau de cet enfant.'
            );
        }
        abort_unless(
            HouseholdLearningPack::query()
                ->where('household_id', $householdId)
                ->where('learning_pack_id', $learningPack->id)
                ->where('is_active', true)
                ->exists(),
            422,
            'Activez ce parcours pour la famille avant de l’attribuer.'
        );

        $assignment = DB::transaction(fn () => ChildLearningPack::query()->updateOrCreate(
            ['child_id' => $childId, 'learning_pack_id' => $learningPack->id],
            [
                'household_id' => $householdId,
                'assigned_by_user_id' => $request->user()?->id,
                'status' => 'assigned',
                'source' => 'manual',
                'completed_at' => null,
            ]
        ));

        return response()->json(['ok' => true, 'assignment' => $assignment], 201);
    }

    public function pause(Request $request, int $childId, LearningPack $learningPack): JsonResponse
    {
        ChildLearningPack::query()
            ->where('household_id', $this->householdId($request))
            ->where('child_id', $childId)
            ->where('learning_pack_id', $learningPack->id)
            ->update(['status' => 'paused', 'updated_at' => now()]);

        return response()->json(['ok' => true]);
    }

    private function householdId(Request $request): int
    {
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 403);

        if ($request->user()) {
            abort_unless(
                $request->user()->households()->whereKey($householdId)->wherePivot('is_active', true)->exists(),
                403
            );
        }

        return $householdId;
    }

    private function ensureAvailable(LearningPack $learningPack): void
    {
        abort_unless(
            $learningPack->visibility === 'global'
                && $learningPack->is_active
                && $learningPack->is_published
                && ($learningPack->type !== 'national_language'
                    || ($learningPack->national_language_id
                        && $learningPack->linguistic_review_status === 'verified'
                        && $learningPack->content_version
                        && $learningPack->linguistic_reviewed_at)),
            404
        );
    }

    private function exercisePayload($exercise): array
    {
        return [
            'id' => $exercise->id,
            'title' => $exercise->title,
            'instructions' => $exercise->instructions,
            'category' => $exercise->category,
            'difficulty' => $exercise->difficulty,
            'subject' => $exercise->lesson?->unit?->integratedTheme?->subject?->name ?? 'General',
            'level_id' => $exercise->lesson?->unit?->integratedTheme?->subject?->level_id,
            'content' => is_array($exercise->content) ? $exercise->content : json_decode($exercise->content, true),
        ];
    }
}

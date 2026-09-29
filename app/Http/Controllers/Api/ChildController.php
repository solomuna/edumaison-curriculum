<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Support\FamilyContext;
use App\Services\NationalLanguageProfileService;
use Illuminate\Validation\Rule;

class ChildController extends Controller
{
    public function update(Request $request, int $childId)
    {
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 403);
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:80'],
            'birth_date' => ['required', 'date', 'before:today'],
            'level_id' => ['required', 'integer', 'exists:levels,id'],
            'pin' => ['nullable', 'string', 'regex:/^\d{4}$/'],
            'avatar' => ['nullable', 'image', 'max:4096'],
            'national_language_mode' => ['sometimes', Rule::in(['inherit', 'catalogue', 'custom'])],
            'national_language_id' => [
                'nullable',
                'required_if:national_language_mode,catalogue',
                Rule::exists('national_languages', 'id')->where(fn ($query) => $query
                    ->where('is_active', true)
                    ->where('is_selectable', true)
                    ->whereNull('deleted_at')),
            ],
            'national_language_other_name' => [
                'nullable', 'string', 'max:120', 'required_if:national_language_mode,custom',
            ],
            'national_language_profile_ids' => ['sometimes', 'array', 'min:1'],
            'national_language_profile_ids.*' => [
                'integer',
                Rule::exists('household_national_language', 'id')->where(fn ($query) => $query
                    ->where('household_id', $householdId)
                    ->where('is_active', true)),
            ],
            'current_national_language_profile_id' => [
                'nullable',
                'integer',
                Rule::exists('household_national_language', 'id')->where(fn ($query) => $query
                    ->where('household_id', $householdId)
                    ->where('is_active', true)),
            ],
        ]);
        $values = [
            'first_name' => $data['first_name'], 'last_name' => $data['last_name'] ?? '',
            'birth_date' => $data['birth_date'], 'level_id' => $data['level_id'], 'updated_at' => now(),
        ];
        if (! empty($data['pin'])) $values['pin_hash'] = Hash::make($data['pin']);
        if ($request->hasFile('avatar')) $values['avatar'] = $request->file('avatar')->store('avatars', 'public');
        if (array_key_exists('national_language_mode', $data)) {
            $values['national_language_id'] = $data['national_language_mode'] === 'catalogue'
                ? (int) $data['national_language_id']
                : null;
            $values['national_language_other_name'] = $data['national_language_mode'] === 'custom'
                ? trim((string) $data['national_language_other_name'])
                : null;
        }
        DB::table('children')->where('id', $childId)->update($values);
        if (array_key_exists('national_language_profile_ids', $data)) {
            app(NationalLanguageProfileService::class)->replaceChildLanguages(
                $childId,
                $householdId,
                $data['national_language_profile_ids'],
                isset($data['current_national_language_profile_id'])
                    ? (int) $data['current_national_language_profile_id']
                    : null
            );
        }
        return response()->json(['ok' => true]);
    }

    public function deactivate(Request $request, int $childId)
    {
        $request->validate(['password' => ['required', 'current_password:web']]);
        DB::table('children')->where('id', $childId)->update(['is_active' => false, 'updated_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function resetProgress(int $childId)
    {
        $schoolYearId = DB::table('school_years')->where('is_current', true)->value('id');
        if (! $schoolYearId) {
            return response()->json(['message' => 'Aucune année scolaire active.'], 409);
        }

        [$deletedAttempts, $audioPaths] = DB::transaction(function () use ($childId, $schoolYearId): array {
            $attemptIds = DB::table('exercise_attempts')
                ->where('child_id', $childId)
                ->where('school_year_id', $schoolYearId)
                ->lockForUpdate()
                ->pluck('id');

            $audioPaths = $attemptIds->isEmpty()
                ? collect()
                : DB::table('pronunciation_attempts')
                    ->whereIn('exercise_attempt_id', $attemptIds)
                    ->whereNotNull('recorded_audio_path')
                    ->pluck('recorded_audio_path');

            $deleted = $attemptIds->isEmpty()
                ? 0
                : DB::table('exercise_attempts')->whereIn('id', $attemptIds)->delete();

            DB::table('child_learning_pack')
                ->where('child_id', $childId)
                ->whereIn('status', ['assigned', 'in_progress', 'completed'])
                ->update([
                    'status' => 'assigned',
                    'started_at' => null,
                    'completed_at' => null,
                    'updated_at' => now(),
                ]);

            return [$deleted, $audioPaths];
        });

        foreach ($audioPaths as $path) {
            Storage::disk('local')->delete($path);
        }

        return response()->json([
            'ok' => true,
            'deleted_attempts' => $deletedAttempts,
            'school_year_id' => (int) $schoolYearId,
        ]);
    }

    public function uploadAvatar(Request $request, int $childId)
    {
        $child = DB::table('children')->where('id', $childId)->first();
        if (!$child) return response()->json(['error' => 'Child not found'], 404);

        $request->validate(['avatar' => 'required|image|max:2048']);

        $path = $request->file('avatar')->store('avatars', 'public');

        DB::table('children')->where('id', $childId)->update([
            'avatar'     => $path,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success'    => true,
            'avatar_url' => asset('storage/' . $path),
        ]);
    }

    // Profil d'un enfant : infos de base + stats de progression (annee courante).
    // Contrat aligne sur FastAPI (id/name/level/level_id/avatar/birth_date) + les
    // compteurs attempts/completed lus par la page profil React. Ne leve jamais
    // d'erreur : 404 propre si l'enfant n'existe pas, sinon zeros.
    public function profile(int $childId)
    {
        $child = DB::table('children')
            ->leftJoin('levels', 'children.level_id', '=', 'levels.id')
            ->where('children.id', $childId)
            ->select('children.*', 'levels.name as level_name')
            ->first();

        if (!$child) return response()->json(['error' => 'Child not found'], 404);

        $schoolYearId = DB::table('school_years')->where('is_current', true)->value('id');

        $base = DB::table('exercise_attempts')
            ->where('child_id', $childId)
            ->when($schoolYearId, fn($q) => $q->where('school_year_id', $schoolYearId));

        $attempts  = (clone $base)->count();
        $completed = (clone $base)->where('status', 'completed')->count();

        return response()->json([
            'id'         => $child->id,
            'name'       => trim($child->first_name . ' ' . $child->last_name),
            'level'      => $child->level_name ?? 'Class 1',
            'level_id'   => $child->level_id,
            'avatar'     => $child->avatar,
            'birth_date' => $child->birth_date,
            'attempts'   => $attempts,
            'completed'  => $completed,
        ]);
    }

    // Level progression map
    private array $nextLevel = [
        2 => 3,   // Pre-Nursery -> Nursery 1
        3 => 4,   // Nursery 1   -> Nursery 2
        4 => 5,   // Nursery 2   -> Class 1
        5 => 6,   // Class 1     -> Class 2
        6 => 7,   // Class 2     -> Class 3
        7 => 8,   // Class 3     -> Class 4
        8 => 9,   // Class 4     -> Class 5
        9 => 10,  // Class 5     -> Class 6
    ];

    public function promote(int $childId)
    {
        $child = DB::table('children')->where('id', $childId)->first();
        if (!$child) return response()->json(['error' => 'Child not found'], 404);

        $nextLevelId = $this->nextLevel[$child->level_id] ?? null;
        if (!$nextLevelId) {
            return response()->json(['error' => 'No next level (Class 6 is the highest)'], 422);
        }

        $nextLevel = DB::table('levels')->where('id', $nextLevelId)->first();

        DB::table('children')->where('id', $childId)->update([
            'level_id'   => $nextLevelId,
            'updated_at' => now(),
        ]);

        // Archive exercise attempts (mark as archived)
        // We keep attempts but just update the child's level
        // Optionally reset streaks for new year
        DB::table('streaks')->where('child_id', $childId)->update([
            'streak'      => 0,
            'best_streak' => DB::raw('streak'),
            'updated_at'  => now(),
        ]);

        return response()->json([
            'success'    => true,
            'child_id'   => $childId,
            'new_level'  => $nextLevel->name ?? '',
            'new_level_id' => $nextLevelId,
        ]);
    }

    public function promoteAll(Request $request)
    {
        $householdId = FamilyContext::householdId($request);
        abort_unless($householdId, 401);
        $children = DB::table('children')->where('household_id', $householdId)->get();
        $promoted = [];
        foreach ($children as $child) {
            $nextLevelId = $this->nextLevel[$child->level_id] ?? null;
            if ($nextLevelId) {
                DB::table('children')->where('id', $child->id)->update([
                    'level_id' => $nextLevelId, 'updated_at' => now()
                ]);
                DB::table('streaks')->where('child_id', $child->id)->update([
                    'streak' => 0, 'best_streak' => DB::raw('streak'), 'updated_at' => now()
                ]);
                $promoted[] = $child->id;
            }
        }
        return response()->json(['success' => true, 'promoted' => $promoted]);
    }
}

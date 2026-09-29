<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NationalLanguageProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class FamilySettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $householdId = $this->householdId($request);
        $family = DB::table('households')->where('id', $householdId)->first();
        $companion = DB::table('mama_profile')->where('household_id', $householdId)->first();
        $profiles = app(NationalLanguageProfileService::class);
        $children = DB::table('children')
            ->where('household_id', $householdId)
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get([
                'id', 'first_name', 'last_name', 'birth_date', 'level_id', 'avatar',
                'national_language_id', 'national_language_other_name',
            ])
            ->map(function (object $child) use ($profiles, $householdId): object {
                $child->national_language_mode = $child->national_language_id
                    ? 'catalogue'
                    : (trim((string) $child->national_language_other_name) !== '' ? 'custom' : 'inherit');
                $child->national_language_profile = $profiles->forChild((int) $child->id, $householdId);
                $child->enabled_national_language_profile_ids = collect($child->national_language_profile['languages'])
                    ->pluck('language_profile_id')
                    ->filter()
                    ->map(fn ($id): int => (int) $id)
                    ->values();
                return $child;
            });

        $familyLanguages = $profiles->householdLanguages($householdId);

        return response()->json([
            'parent' => ['name' => $request->user()->name, 'email' => $request->user()->email],
            'family' => [
                'name' => $family?->name,
                'city' => $family?->city,
                'school' => $family?->school,
                'national_language_mode' => $family?->national_language_id
                    ? 'catalogue'
                    : (trim((string) $family?->national_language_other_name) !== '' ? 'custom' : 'none'),
                'national_language_id' => $family?->national_language_id,
                'national_language_other_name' => $family?->national_language_other_name,
                'national_language_profile_ids' => collect($familyLanguages)
                    ->pluck('language_profile_id')->map(fn ($id): int => (int) $id)->values(),
            ],
            'companion' => ['display_name' => $companion?->display_name ?? $request->user()->name, 'avatar' => $companion?->avatar],
            'national_languages' => DB::table('national_languages')
                ->where('is_active', true)
                ->where('is_selectable', true)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get(['id', 'code', 'name', 'autonym']),
            'family_languages' => $familyLanguages,
            'children' => $children,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'parent_name' => ['required', 'string', 'max:120'],
            'family_name' => ['required', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'school' => ['nullable', 'string', 'max:190'],
            'parent_photo' => ['nullable', 'image', 'max:4096'],
            'national_language_mode' => ['sometimes', Rule::in(['none', 'catalogue', 'custom'])],
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
            'national_language_ids' => ['sometimes', 'array'],
            'national_language_ids_present' => ['sometimes', 'boolean'],
            'national_language_ids.*' => [
                'integer',
                Rule::exists('national_languages', 'id')->where(fn ($query) => $query
                    ->where('is_active', true)
                    ->where('is_selectable', true)
                    ->whereNull('deleted_at')),
            ],
            'national_language_other_names' => ['sometimes', 'array', 'max:4'],
            'national_language_other_names.*' => ['nullable', 'string', 'max:120'],
            'national_language_variants' => ['sometimes', 'array'],
            'national_language_variants.*' => ['nullable', 'string', 'max:120'],
        ]);
        $householdId = $this->householdId($request);
        $request->user()->update(['name' => $data['parent_name']]);
        $householdValues = [
            'name' => $data['family_name'], 'city' => $data['city'] ?? null,
            'school' => $data['school'] ?? null, 'updated_at' => now(),
        ];
        if (array_key_exists('national_language_mode', $data)) {
            $householdValues['national_language_id'] = $data['national_language_mode'] === 'catalogue'
                ? (int) $data['national_language_id']
                : null;
            $householdValues['national_language_other_name'] = $data['national_language_mode'] === 'custom'
                ? trim((string) $data['national_language_other_name'])
                : null;
        }
        DB::table('households')->where('id', $householdId)->update($householdValues);
        if (! empty($data['national_language_ids_present'])) {
            app(NationalLanguageProfileService::class)->replaceHouseholdLanguages(
                $householdId,
                $data['national_language_ids'] ?? [],
                $data['national_language_other_names'] ?? [],
                $data['national_language_variants'] ?? []
            );
        }
        $profile = DB::table('mama_profile')->where('household_id', $householdId)->first();
        $values = ['display_name' => $data['parent_name'], 'updated_at' => now()];
        if ($request->hasFile('parent_photo')) $values['avatar'] = $request->file('parent_photo')->store('avatars', 'public');
        if ($profile) DB::table('mama_profile')->where('id', $profile->id)->update($values);
        else DB::table('mama_profile')->insert(array_merge($values, [
            'household_id' => $householdId, 'relationship' => 'parent', 'tone' => 'encouraging',
            'language' => 'fr', 'created_at' => now(),
        ]));
        return $this->show($request);
    }

    public function updatePin(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password' => ['required', 'current_password:web'],
            'pin' => ['required', 'string', 'regex:/^\d{4}$/', 'confirmed'],
        ]);
        DB::table('households')->where('id', $this->householdId($request))->update([
            'access_pin_hash' => Hash::make($data['pin']), 'updated_at' => now(),
        ]);
        return response()->json(['ok' => true]);
    }

    private function householdId(Request $request): int
    {
        $id = (int) $request->session()->get('household_id');
        abort_unless($id > 0 && $request->user()->households()->whereKey($id)->wherePivot('is_active', true)->exists(), 403);
        return $id;
    }
}

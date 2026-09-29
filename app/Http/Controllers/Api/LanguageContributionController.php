<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LanguageContribution;
use App\Support\FamilyContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LanguageContributionController extends Controller
{
    public function index(Request $request)
    {
        $householdId = $this->householdId($request);

        $languages = DB::table('household_national_language as profile')
            ->leftJoin('national_languages as language', 'language.id', '=', 'profile.national_language_id')
            ->where('profile.household_id', $householdId)
            ->where('profile.is_active', true)
            ->orderBy('profile.priority')
            ->orderBy('profile.id')
            ->get([
                'profile.id', 'profile.variant_name', 'profile.family_label', 'profile.custom_name',
                'language.code', 'language.name as catalogue_name', 'language.autonym',
            ])->map(fn ($language) => [
                'id' => (int) $language->id,
                'code' => $language->code,
                'catalogue_name' => $language->catalogue_name,
                'variant_name' => $language->variant_name,
                'display_name' => $language->variant_name
                    ?: $language->family_label
                    ?: $language->custom_name
                    ?: $language->autonym
                    ?: $language->catalogue_name,
            ])->values();

        $contributions = LanguageContribution::query()
            ->where('household_id', $householdId)
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn (LanguageContribution $item) => $this->present($item));

        return response()->json(['languages' => $languages, 'contributions' => $contributions]);
    }

    public function store(Request $request)
    {
        $householdId = $this->householdId($request);
        $data = $this->validated($request, $householdId);
        $profile = $this->languageProfile($householdId, (int) $data['language_profile_id']);
        $submit = ($data['action'] ?? 'draft') === 'submit';
        if ($submit && ! $data['rights_confirmed']) {
            throw ValidationException::withMessages([
                'rights_confirmed' => 'Confirmez les droits et le consentement avant la soumission.',
            ]);
        }

        $item = LanguageContribution::query()->create([
            'household_id' => $householdId,
            'household_national_language_id' => (int) $profile->id,
            'created_by_user_id' => $request->user()->id,
            'variant_name' => $profile->variant_name ?: $profile->family_label ?: $profile->custom_name,
            'kind' => $data['kind'],
            'source_text' => trim($data['source_text']),
            'french_translation' => trim($data['french_translation']),
            'english_translation' => trim($data['english_translation']),
            'usage_context' => trim($data['usage_context']),
            'source_origin' => $data['source_origin'],
            'source_reference' => isset($data['source_reference']) ? trim($data['source_reference']) : null,
            'rights_confirmed' => (bool) $data['rights_confirmed'],
            'status' => $submit ? 'submitted' : 'draft',
            'submitted_at' => $submit ? now() : null,
        ]);

        return response()->json(['contribution' => $this->present($item)], 201);
    }

    public function update(Request $request, LanguageContribution $contribution)
    {
        $householdId = $this->householdId($request);
        $this->ownedDraft($contribution, $householdId);
        $data = $this->validated($request, $householdId);
        $profile = $this->languageProfile($householdId, (int) $data['language_profile_id']);

        $contribution->update([
            'household_national_language_id' => (int) $profile->id,
            'variant_name' => $profile->variant_name ?: $profile->family_label ?: $profile->custom_name,
            'kind' => $data['kind'],
            'source_text' => trim($data['source_text']),
            'french_translation' => trim($data['french_translation']),
            'english_translation' => trim($data['english_translation']),
            'usage_context' => trim($data['usage_context']),
            'source_origin' => $data['source_origin'],
            'source_reference' => isset($data['source_reference']) ? trim($data['source_reference']) : null,
            'rights_confirmed' => (bool) $data['rights_confirmed'],
        ]);

        return response()->json(['contribution' => $this->present($contribution->fresh())]);
    }

    public function submit(Request $request, LanguageContribution $contribution)
    {
        $householdId = $this->householdId($request);
        $this->ownedDraft($contribution, $householdId);
        if (! $contribution->rights_confirmed) {
            throw ValidationException::withMessages([
                'rights_confirmed' => 'Confirmez les droits et le consentement avant la soumission.',
            ]);
        }

        $contribution->update(['status' => 'submitted', 'submitted_at' => now()]);

        return response()->json(['contribution' => $this->present($contribution->fresh())]);
    }

    public function destroy(Request $request, LanguageContribution $contribution)
    {
        $householdId = $this->householdId($request);
        $this->ownedDraft($contribution, $householdId);
        $contribution->delete();

        return response()->noContent();
    }

    private function validated(Request $request, int $householdId): array
    {
        return $request->validate([
            'language_profile_id' => [
                'required', 'integer',
                Rule::exists('household_national_language', 'id')->where(
                    fn ($query) => $query->where('household_id', $householdId)->where('is_active', true)
                ),
            ],
            'kind' => ['required', Rule::in(['word', 'expression', 'short_sentence'])],
            'source_text' => ['required', 'string', 'max:160'],
            'french_translation' => ['required', 'string', 'max:200'],
            'english_translation' => ['required', 'string', 'max:200'],
            'usage_context' => ['required', 'string', 'max:1000'],
            'source_origin' => ['required', Rule::in(['adult_speaker', 'family_creation', 'authorized_reference'])],
            'source_reference' => ['nullable', 'string', 'max:500', 'required_if:source_origin,authorized_reference'],
            'rights_confirmed' => ['required', 'boolean'],
            'action' => ['sometimes', Rule::in(['draft', 'submit'])],
        ]);
    }

    private function householdId(Request $request): int
    {
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 401);
        $userId = (int) ($request->user()?->id ?? 0);
        abort_unless($userId > 0 && DB::table('household_user')
            ->where('household_id', $householdId)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->exists(), 403);

        return $householdId;
    }

    private function languageProfile(int $householdId, int $profileId): object
    {
        return DB::table('household_national_language')
            ->where('id', $profileId)
            ->where('household_id', $householdId)
            ->where('is_active', true)
            ->firstOrFail(['id', 'variant_name', 'family_label', 'custom_name']);
    }

    private function ownedDraft(LanguageContribution $contribution, int $householdId): void
    {
        abort_unless((int) $contribution->household_id === $householdId, 404);
        abort_unless($contribution->status === 'draft', 409, 'Une contribution soumise ne peut plus etre modifiee.');
    }

    private function present(LanguageContribution $item): array
    {
        return [
            'id' => $item->id,
            'language_profile_id' => $item->household_national_language_id,
            'variant_name' => $item->variant_name,
            'kind' => $item->kind,
            'source_text' => $item->source_text,
            'french_translation' => $item->french_translation,
            'english_translation' => $item->english_translation,
            'usage_context' => $item->usage_context,
            'source_origin' => $item->source_origin,
            'source_reference' => $item->source_reference,
            'rights_confirmed' => $item->rights_confirmed,
            'status' => $item->status,
            'submitted_at' => $item->submitted_at,
            'created_at' => $item->created_at,
        ];
    }
}

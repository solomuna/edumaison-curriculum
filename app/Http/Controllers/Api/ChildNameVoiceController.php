<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SyncChildNameVoice;
use App\Models\Child;
use App\Models\ChildVoiceClip;
use App\Models\Household;
use App\Services\ChildNameVoice;
use App\Support\FamilyContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Mama Judi au prénom de l'enfant : accord du parent, liste et lecture des répliques. */
class ChildNameVoiceController extends Controller
{
    public function settings(Request $request, ChildNameVoice $voice)
    {
        $household = $this->parentHousehold($request);
        return response()->json([
            'enabled' => (bool) $household->child_name_voice_consent_at,
            'available' => $voice->configured(),
        ]);
    }

    public function updateSettings(Request $request, ChildNameVoice $voice)
    {
        $household = $this->parentHousehold($request);
        $data = $request->validate(['enabled' => ['required', 'boolean']]);
        abort_if($data['enabled'] && ! $voice->configured(), 422, 'The name voice is not available yet.');

        $household->update([
            'child_name_voice_consent_at' => $data['enabled'] ? ($household->child_name_voice_consent_at ?? now()) : null,
        ]);

        $children = Child::query()->where('household_id', $household->id)->get();
        foreach ($children as $child) {
            // Accord retiré : effacement immédiat ; accordé : génération en arrière-plan.
            if ($data['enabled']) SyncChildNameVoice::dispatch($child->id);
            else $voice->purge($child);
        }

        return $this->settings($request, $voice);
    }

    /** Répliques disponibles pour l'enfant connecté (vide sans accord). */
    public function manifest(Request $request, int $childId)
    {
        $child = $this->familyChild($request, $childId);
        $clips = [];
        if ($child->household?->child_name_voice_consent_at) {
            foreach (ChildVoiceClip::query()->where('child_id', $child->id)->orderBy('variant')->get() as $clip) {
                $clips[$clip->language][$clip->event][] = ['url' => "/api/children/{$child->id}/name-voice/{$clip->id}"];
            }
        }
        return response()->json(['clips' => (object) $clips])->header('Cache-Control', 'private, no-store');
    }

    public function audio(Request $request, int $childId, int $clipId)
    {
        $child = $this->familyChild($request, $childId);
        abort_unless($child->household?->child_name_voice_consent_at, 404);
        $clip = ChildVoiceClip::query()->where('child_id', $child->id)->findOrFail($clipId);
        abort_unless(Storage::disk('local')->exists($clip->file_path), 404);
        return Storage::disk('local')->response($clip->file_path, null, [
            'Content-Type' => 'audio/mpeg',
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    private function parentHousehold(Request $request): Household
    {
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 401);
        abort_unless($request->user()?->households()->whereKey($householdId)->wherePivot('is_active', true)->exists(), 403);
        return Household::query()->findOrFail($householdId);
    }

    /** L'enfant doit appartenir au foyer de la session (isolation stricte entre familles). */
    private function familyChild(Request $request, int $childId): Child
    {
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 401);
        return Child::query()->with('household')->where('household_id', $householdId)->findOrFail($childId);
    }
}

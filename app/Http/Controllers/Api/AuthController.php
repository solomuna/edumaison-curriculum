<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Support\FamilyContext;
use App\Services\NationalLanguageProfileService;

class AuthController extends Controller
{
    // Liste des enfants (pour la page de sélection)
    public function children(Request $request)
    {
        $householdId = FamilyContext::householdId($request);
        abort_unless($householdId, 401);
        $query = Child::with("level")->where("is_active", true)->where('household_id', $householdId);

        $profiles = app(NationalLanguageProfileService::class);
        $children = $query->get()
            ->map(fn($c) => [
                "id"         => $c->id,
                "name"       => $c->first_name . " " . $c->last_name,
                "level"      => $c->level?->name ?? "Class 1",
                "level_id"   => $c->level_id,
                "avatar"     => $c->avatar,
                "birth_date" => $c->birth_date,
                "national_language" => $profiles->forChild((int) $c->id, $householdId),
            ]);

        return response()->json($children);
    }

    // Vérification du PIN
    public function login(Request $request)
    {
        $request->validate([
            "child_id" => "required|integer",
            "pin"      => "required|string|max:4",
        ]);

        $householdId = FamilyContext::householdId($request);
        $child = Child::with("level")->where('household_id', $householdId)->find($request->child_id);

        $pinValid = $child && ($child->pin_hash
            ? Hash::check($request->pin, $child->pin_hash)
            : hash_equals((string) $child->pin, (string) $request->pin));

        if (!$pinValid) {
            return response()->json(["error" => "PIN incorrect"], 401);
        }

        if (!$child->pin_hash) {
            $child->forceFill(['pin_hash' => Hash::make($request->pin), 'pin' => null])->save();
        }

        return response()->json([
            "id"         => $child->id,
            "name"       => $child->first_name . " " . $child->last_name,
            "level"      => $child->level?->name ?? "Class 1",
            "level_id"   => $child->level_id,
            "avatar"     => $child->avatar,
            "birth_date" => $child->birth_date,
            "national_language" => app(NationalLanguageProfileService::class)
                ->forChild((int) $child->id, (int) $householdId),
        ]);
    }
}

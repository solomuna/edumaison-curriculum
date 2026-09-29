<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NationalLanguageProfileService;
use App\Support\FamilyContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NationalLanguageProfileController extends Controller
{
    public function show(Request $request, int $childId, NationalLanguageProfileService $profiles): JsonResponse
    {
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 403);

        return response()->json($profiles->forChild($childId, $householdId));
    }

    public function selectCurrent(Request $request, int $childId, NationalLanguageProfileService $profiles): JsonResponse
    {
        $data = $request->validate([
            'household_language_id' => ['required', 'integer'],
        ]);
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 403);

        return response()->json($profiles->selectCurrentLanguage(
            $childId,
            $householdId,
            (int) $data['household_language_id']
        ));
    }
}

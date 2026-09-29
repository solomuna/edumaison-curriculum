<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Throwable;
use Illuminate\Support\Facades\DB;

class AccessController extends Controller
{
    private const COOKIE = 'edumaison_access';
    private const MINUTES = 60 * 24 * 30;

    public function status(Request $request): JsonResponse
    {
        $householdId = self::householdIdFromCookie($request);
        return response()->json([
            'unlocked' => $householdId !== null,
            'family' => $householdId ? DB::table('households')->where('id', $householdId)->first(['name']) : null,
        ]);
    }

    public function unlock(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pin' => ['required', 'string', 'regex:/^\d{4}$/'],
        ]);

        $hash = (string) config('access.pin_hash');
        if ($hash === '' || ! password_verify($validated['pin'], $hash)) {
            return response()->json([
                'message' => 'Ce code ne fonctionne pas. Réessaie doucement.',
            ], 422);
        }

        $householdId = (int) config('access.legacy_household_id');
        if ($householdId < 1 || ! DB::table('households')->where('id', $householdId)->where('is_active', true)->exists()) {
            return response()->json(['message' => 'Accès historique non configuré.'], 503);
        }
        $expiresAt = now()->addMinutes(self::MINUTES)->timestamp;
        $token = Crypt::encryptString(json_encode(['expires_at' => $expiresAt, 'household_id' => $householdId]));

        return response()
            ->json(['unlocked' => true, 'family' => ['name' => DB::table('households')->where('id', $householdId)->value('name')]])
            ->cookie(self::COOKIE, $token, self::MINUTES, '/', null, true, true, false, 'Lax');
    }

    public function lock(): JsonResponse
    {
        return response()
            ->json(['unlocked' => false])
            ->withoutCookie(self::COOKIE, '/');
    }

    public static function hasValidCookie(Request $request): bool
    {
        return self::householdIdFromCookie($request) !== null;
    }

    public static function householdIdFromCookie(Request $request): ?int
    {
        $token = $request->cookie(self::COOKIE);
        if (! is_string($token) || $token === '') {
            return null;
        }

        try {
            $payload = json_decode(Crypt::decryptString($token), true, flags: JSON_THROW_ON_ERROR);
            $householdId = (int) ($payload['household_id'] ?? 0);
            return is_array($payload) && (int) ($payload['expires_at'] ?? 0) > now()->timestamp && $householdId > 0 ? $householdId : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function isUnlocked(Request $request): bool
    {
        return self::hasValidCookie($request);
    }
}

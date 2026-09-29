<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Support\FamilyContext;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RequireMamaAccess
{
    public const COOKIE = 'edumaison_mama_access';

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->hasValidCookie($request)) {
            return response()->json(['message' => 'Code Mama requis.'], 403)
                ->header('X-EduMaison-Mama-Access', 'required');
        }

        return $next($request);
    }

    private function hasValidCookie(Request $request): bool
    {
        $token = $request->cookie(self::COOKIE);
        if (! is_string($token) || $token === '') return false;

        try {
            $payload = json_decode(Crypt::decryptString($token), true, flags: JSON_THROW_ON_ERROR);
            if (! is_array($payload) || (int) ($payload['expires_at'] ?? 0) <= now()->timestamp) return false;

            $expectedHousehold = FamilyContext::householdId($request);
            $cookieHousehold = isset($payload['household_id']) ? (int) $payload['household_id'] : null;

            return $expectedHousehold !== null && $cookieHousehold === $expectedHousehold;
        } catch (Throwable) {
            return false;
        }
    }
}

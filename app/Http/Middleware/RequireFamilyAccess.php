<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\AccessController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireFamilyAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->session()->get('family_locked', false)) {
            return $next($request);
        }

        if ($request->user() && $request->session()->get('family_locked', false)) {
            return response()->json(['message' => 'Espace familial verrouillé.'], 423)
                ->header('X-EduMaison-Family-Locked', 'required');
        }

        if (AccessController::hasValidCookie($request)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Connexion familiale requise.',
        ], 401)->header('X-EduMaison-Access', 'required');
    }
}

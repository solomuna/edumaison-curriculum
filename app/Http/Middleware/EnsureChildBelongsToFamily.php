<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Support\FamilyContext;

class EnsureChildBelongsToFamily
{
    public function handle(Request $request, Closure $next): Response
    {
        $householdId = FamilyContext::householdId($request);
        if (! $householdId) return response()->json(['message' => 'Contexte familial requis.'], 401);
        $ids = [];
        foreach (['childId', 'id'] as $parameter) {
            if ($request->route($parameter) !== null) $ids[] = (int) $request->route($parameter);
        }
        foreach (['child_id', 'child1_id', 'child2_id'] as $field) {
            if ($request->filled($field)) $ids[] = (int) $request->input($field);
        }
        foreach ((array) $request->input('child_ids', []) as $id) $ids[] = (int) $id;
        $ids = array_values(array_unique(array_filter($ids)));

        if ($ids && DB::table('children')->whereIn('id', $ids)->where('household_id', $householdId)->count() !== count($ids)) {
            return response()->json(['message' => 'Enfant introuvable.'], 404);
        }

        return $next($request);
    }
}

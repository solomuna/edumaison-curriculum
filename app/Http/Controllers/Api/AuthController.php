<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Liste des enfants (pour la page de sélection)
    public function children()
    {
        $children = Child::with("level")
            ->where("is_active", true)
            ->get()
            ->map(fn($c) => [
                "id"         => $c->id,
                "name"       => $c->first_name . " " . $c->last_name,
                "level"      => $c->level?->name ?? "Class 1",
                "level_id"   => $c->level_id,
                "avatar"     => $c->avatar,
                "birth_date" => $c->birth_date,
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

        $child = Child::with("level")->find($request->child_id);

        if (!$child || $child->pin !== $request->pin) {
            return response()->json(["error" => "PIN incorrect"], 401);
        }

        return response()->json([
            "id"         => $child->id,
            "name"       => $child->first_name . " " . $child->last_name,
            "level"      => $child->level?->name ?? "Class 1",
            "level_id"   => $child->level_id,
            "avatar"     => $child->avatar,
            "birth_date" => $child->birth_date,
        ]);
    }
}
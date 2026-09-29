<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FamilyOnboardingController extends Controller
{
    public function levels(): JsonResponse
    {
        return response()->json(Level::query()->orderBy('id')->get(['id', 'name']));
    }

    public function storeChild(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:80'],
            'birth_date' => ['required', 'date', 'before:today'],
            'level_id' => ['required', 'integer', 'exists:levels,id'],
            'pin' => ['required', 'string', 'regex:/^\d{4}$/'],
            'avatar' => ['nullable', 'image', 'max:4096'],
        ]);

        $householdId = (int) $request->session()->get('household_id');
        abort_unless($request->user()->households()->whereKey($householdId)->wherePivot('is_active', true)->exists(), 403);

        $avatar = $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null;
        $child = Child::create([
            'household_id' => $householdId,
            'level_id' => $data['level_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? '',
            'birth_date' => $data['birth_date'],
            'pin_hash' => Hash::make($data['pin']),
            'is_active' => true,
            'avatar' => $avatar,
        ]);

        return response()->json(['id' => $child->id, 'first_name' => $child->first_name], 201);
    }
}

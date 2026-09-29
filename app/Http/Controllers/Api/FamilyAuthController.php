<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class FamilyAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'family_name' => ['required', 'string', 'max:120'],
            'family_pin' => ['required', 'string', 'regex:/^\d{4}$/', 'confirmed'],
            'parent_photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $parentAvatar = $request->hasFile('parent_photo')
            ? $request->file('parent_photo')->store('avatars', 'public')
            : null;

        [$user, $household] = DB::transaction(function () use ($data, $parentAvatar) {
            $user = User::create([
                'name' => $data['name'],
                'email' => mb_strtolower($data['email']),
                'password' => $data['password'],
            ]);
            $household = Household::create([
                'name' => $data['family_name'],
                'access_pin_hash' => Hash::make($data['family_pin']),
            ]);
            $household->users()->attach($user->id, ['role' => 'owner', 'is_active' => true]);
            DB::table('mama_profile')->insert([
                'household_id' => $household->id,
                'display_name' => $data['name'],
                'relationship' => 'parent',
                'tone' => 'encouraging',
                'language' => 'fr',
                'avatar' => $parentAvatar,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            return [$user, $household];
        });

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('household_id', $household->id);
        $request->session()->put('family_locked', false);

        return response()->json($this->payload($user, $household), 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => mb_strtolower($credentials['email']), 'password' => $credentials['password']], true)) {
            return response()->json(['message' => 'Adresse e-mail ou mot de passe incorrect.'], 422);
        }

        $request->session()->regenerate();
        $user = $request->user();
        $household = $user->households()->wherePivot('is_active', true)->first();
        if (! $household) {
            Auth::logout();
            return response()->json(['message' => 'Aucune famille active n’est liée à ce compte.'], 403);
        }
        $request->session()->put('household_id', $household->id);
        $request->session()->put('family_locked', false);

        return response()->json($this->payload($user, $household));
    }

    public function claimLegacy(Request $request): JsonResponse
    {
        $householdId = AccessController::householdIdFromCookie($request);
        abort_unless($householdId, 403, 'Accès familial requis.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        [$user, $household] = DB::transaction(function () use ($data, $householdId) {
            $household = Household::query()->whereKey($householdId)->where('is_active', true)->lockForUpdate()->firstOrFail();
            abort_if(
                $household->users()->wherePivot('is_active', true)->exists(),
                409,
                'Cette famille possède déjà un compte parent.'
            );

            $user = User::create([
                'name' => $data['name'],
                'email' => mb_strtolower($data['email']),
                'password' => $data['password'],
            ]);
            $household->users()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

            return [$user, $household];
        });

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('household_id', $household->id);
        $request->session()->put('family_locked', false);

        return response()->json($this->payload($user, $household), 201);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $householdId = (int) $request->session()->get('household_id');
        $household = $user->households()->whereKey($householdId)->wherePivot('is_active', true)->first()
            ?? $user->households()->wherePivot('is_active', true)->firstOrFail();

        $request->session()->put('household_id', $household->id);
        if ($request->session()->get('family_locked', false)) {
            return response()->json([
                'locked' => true,
                'family' => ['id' => $household->id, 'name' => $household->name],
            ], 423);
        }

        return response()->json($this->payload($user, $household));
    }

    public function lock(Request $request): JsonResponse
    {
        $request->session()->put('family_locked', true);
        return response()->json(['locked' => true]);
    }

    public function unlockPin(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pin' => ['required', 'string', 'regex:/^\d{4}$/'],
        ]);
        $household = $this->currentHousehold($request);

        if (! $household->access_pin_hash || ! Hash::check($data['pin'], $household->access_pin_hash)) {
            return response()->json(['message' => 'PIN familial incorrect.'], 422);
        }

        $request->session()->put('family_locked', false);
        return response()->json($this->payload($request->user(), $household));
    }

    public function setPin(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pin' => ['required', 'string', 'regex:/^\d{4}$/', 'confirmed'],
            'password' => ['required', 'current_password:web'],
        ]);
        $household = $this->currentHousehold($request);
        $household->update(['access_pin_hash' => Hash::make($data['pin'])]);
        $request->session()->put('family_locked', false);

        return response()->json(['ok' => true]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['ok' => true]);
    }

    private function payload(User $user, Household $household): array
    {
        return [
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'family' => ['id' => $household->id, 'name' => $household->name],
            'needs_family_pin' => ! $household->access_pin_hash,
        ];
    }

    private function currentHousehold(Request $request): Household
    {
        $householdId = (int) $request->session()->get('household_id');
        return $request->user()->households()
            ->whereKey($householdId)
            ->wherePivot('is_active', true)
            ->firstOrFail();
    }
}

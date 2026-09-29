<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\RequireMamaAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Support\FamilyContext;

class MamaProfileController extends Controller
{
    public function getProfile(Request $request): JsonResponse
    {
        $row = $this->profileQuery($request)->first();
        return response()->json($this->payload($row));
    }

    public function verifyPin(Request $request): JsonResponse
    {
        $pin = (string) $request->input('pin', '');
        $row = $this->profileQuery($request)->first();
        $stored = (string) ($row->pin_hash ?? '');

        if ($stored === '' && $this->householdId($request)) {
            $stored = (string) DB::table('households')->where('id', $this->householdId($request))->value('access_pin_hash');
        }

        $valid = $stored !== '' && (str_starts_with($stored, '$')
            ? Hash::check($pin, $stored)
            : hash_equals($stored, $pin));
        $response = response()->json(['valid' => $valid]);

        if ($valid) {
            $token = Crypt::encryptString(json_encode([
                'expires_at' => now()->addHours(8)->timestamp,
                'household_id' => $this->householdId($request),
            ]));
            $response->cookie(RequireMamaAccess::COOKIE, $token, 480, '/', null, true, true, false, 'Lax');
        }

        return $response;
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:80'],
            'relationship' => ['required', 'in:mama,papa,tata,oncle,grand_parent,tuteur,coach,parent'],
            'child_address' => ['nullable', 'string', 'max:80'],
            'tone' => ['required', 'in:gentle,encouraging,dynamic,firm_kind'],
            'language' => ['required', 'in:fr,en'],
        ]);

        $profile = $this->upsertProfile($request, $data);
        return response()->json(['success' => true, 'profile' => $this->payload($profile)]);
    }

    public function updatePin(Request $request): JsonResponse
    {
        $data = $request->validate(['pin' => ['required', 'string', 'regex:/^\d{4}$/']]);
        $this->upsertProfile($request, ['pin_hash' => Hash::make($data['pin'])]);
        return response()->json(['success' => true]);
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate(['avatar' => ['required', 'image', 'max:2048']]);
        $scope = $this->householdId($request) ?: 'legacy';
        $filename = 'companion_'.$scope.'_'.now()->timestamp.'.'.$request->file('avatar')->getClientOriginalExtension();
        $request->file('avatar')->storeAs('avatars', $filename, 'public');
        $profile = $this->upsertProfile($request, ['avatar' => 'avatars/'.$filename]);

        return response()->json(['success' => true, 'avatar' => $profile->avatar]);
    }

    private function profileQuery(Request $request)
    {
        return DB::table('mama_profile')->where('household_id', $this->householdId($request));
    }

    private function householdId(Request $request): ?int
    {
        $id = (int) (FamilyContext::householdId($request) ?? 0);
        return $id > 0 ? $id : null;
    }

    private function upsertProfile(Request $request, array $values): object
    {
        $householdId = $this->householdId($request);
        $existing = $this->profileQuery($request)->first();
        $values['updated_at'] = now();
        if ($existing) {
            DB::table('mama_profile')->where('id', $existing->id)->update($values);
            return DB::table('mama_profile')->where('id', $existing->id)->first();
        }

        $id = DB::table('mama_profile')->insertGetId(array_merge([
            'household_id' => $householdId,
            'display_name' => 'Mon accompagnateur',
            'relationship' => 'parent',
            'tone' => 'encouraging',
            'language' => 'fr',
            'created_at' => now(),
        ], $values));
        return DB::table('mama_profile')->where('id', $id)->first();
    }

    private function payload(?object $row): array
    {
        return [
            'avatar' => $row?->avatar,
            'display_name' => $row?->display_name ?? 'Mon accompagnateur',
            'relationship' => $row?->relationship ?? 'parent',
            'child_address' => $row?->child_address,
            'tone' => $row?->tone ?? 'encouraging',
            'language' => $row?->language ?? 'fr',
            'has_pin' => (bool) ($row?->pin_hash ?? false),
        ];
    }
}

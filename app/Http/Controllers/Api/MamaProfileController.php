<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MamaProfileController extends Controller
{
    public function getProfile()
    {
        $row = DB::table('mama_profile')->first();
        if (!$row) return response()->json(['avatar' => null, 'has_pin' => true]);
        return response()->json(['avatar' => $row->avatar, 'has_pin' => true]);
    }

    public function verifyPin(Request $request)
    {
        $pin = $request->input('pin', '');
        $row = DB::table('mama_profile')->first();
        if (!$row) return response()->json(['valid' => $pin === '0505']);
        return response()->json(['valid' => $row->pin_hash === $pin]);
    }

    public function updatePin(Request $request)
    {
        $pin = $request->input('pin', '');
        if (strlen($pin) !== 4 || !ctype_digit($pin)) {
            return response()->json(['error' => 'PIN doit etre 4 chiffres'], 422);
        }
        DB::table('mama_profile')->update(['pin_hash' => $pin, 'updated_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function updateAvatar(Request $request)
    {
        if (!$request->hasFile('avatar')) {
            return response()->json(['error' => 'Image requise'], 422);
        }
        $file = $request->file('avatar');
        $filename = 'mama_judi.' . $file->getClientOriginalExtension();
        $file->storeAs('avatars', $filename, 'public');
        DB::table('mama_profile')->update(['avatar' => 'avatars/' . $filename, 'updated_at' => now()]);
        return response()->json(['success' => true, 'avatar' => 'avatars/' . $filename]);
    }
}

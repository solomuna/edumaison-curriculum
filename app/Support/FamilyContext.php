<?php
namespace App\Support;
use App\Http\Controllers\Api\AccessController;
use Illuminate\Http\Request;
class FamilyContext {
    public static function householdId(Request $request): ?int {
        if ($request->user()) { $id = (int) $request->session()->get('household_id'); return $id > 0 ? $id : null; }
        return AccessController::householdIdFromCookie($request);
    }
}

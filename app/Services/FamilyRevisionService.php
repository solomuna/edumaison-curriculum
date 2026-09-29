<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;
class FamilyRevisionService {
    private const BASE_URL = 'http://edumaison-api:8100/api';
    public function trigger(int $householdId, ?array $requestedIds = null): array {
        $allowed = DB::table('children')->where('household_id', $householdId)->where('is_active', true)->pluck('id')->map(fn($id)=>(int)$id);
        $ids = $requestedIds ? $allowed->intersect(array_map('intval', $requestedIds))->values() : $allowed->values();
        if ($ids->isEmpty()) throw new RuntimeException('AUCUN_ENFANT_ACTIF');
        $response = Http::acceptJson()->timeout(20)->post(self::BASE_URL.'/evening-sessions', [
            'child_ids' => $ids->all(), 'subject_ids' => null, 'subject_id' => null, 'unit_id' => null,
            'theme_source' => 'auto', 'mama_judi_message' => 'Bonsoir ! Ton accompagnateur a préparé ta révision selon ton parcours EduMaison.',
        ]);
        if (!$response->successful()) throw new RuntimeException('SERVICE_REVISION_INDISPONIBLE_'.$response->status());
        return ['child_ids'=>$ids->all(), 'upstream'=>$response->json()];
    }
}

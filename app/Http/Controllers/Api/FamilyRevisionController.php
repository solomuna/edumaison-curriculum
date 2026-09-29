<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\FamilyRevisionService;
use App\Support\FamilyContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class FamilyRevisionController extends Controller {
    public function show(Request $request): JsonResponse {
        $householdId=$this->householdId($request); $row=DB::table('family_revision_settings')->where('household_id',$householdId)->first();
        return response()->json(['enabled'=>(bool)($row?->enabled??false),'hour'=>(int)substr($row?->revision_time??'19:00',0,2),'minute'=>(int)substr($row?->revision_time??'19:00',3,2),'child_ids'=>$row?json_decode($row->child_ids??'[]',true):[]]);
    }
    public function update(Request $request): JsonResponse {
        $householdId=$this->householdId($request); $data=$request->validate(['enabled'=>['required','boolean'],'hour'=>['required','integer','between:0,23'],'minute'=>['required','integer','between:0,59'],'child_ids'=>['array'],'child_ids.*'=>['integer']]);
        $allowed=DB::table('children')->where('household_id',$householdId)->where('is_active',true)->pluck('id')->map(fn($id)=>(int)$id);
        $ids=$allowed->intersect(array_map('intval',$data['child_ids']??[]))->values()->all();
        $values=['enabled'=>$data['enabled'],'revision_time'=>sprintf('%02d:%02d',$data['hour'],$data['minute']),'child_ids'=>json_encode($ids),'updated_at'=>now()];
        if (!DB::table('family_revision_settings')->where('household_id',$householdId)->exists()) $values['created_at']=now();
        DB::table('family_revision_settings')->updateOrInsert(['household_id'=>$householdId],$values);
        return $this->show($request);
    }
    public function trigger(Request $request, FamilyRevisionService $service): JsonResponse {
        $householdId=$this->householdId($request); $row=DB::table('family_revision_settings')->where('household_id',$householdId)->first();
        $result=$service->trigger($householdId,$row?json_decode($row->child_ids??'[]',true):null);
        return response()->json(['ok'=>true]+$result);
    }
    private function householdId(Request $request): int { $id=(int)(FamilyContext::householdId($request)??0); abort_unless($id>0,401,'Accès familial requis.'); return $id; }
}

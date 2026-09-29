<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class MamaBridgeController extends Controller
{
    private const BASE_URL = 'http://edumaison-api:8100/api';

    public function brief(Request $request): Response
    {
        try {
            $upstream = Http::acceptJson()->timeout(15)->get(self::BASE_URL.'/mama/brief');
            if (! $upstream->successful() || ! $request->user()) return $this->relay($upstream);

            $data = $upstream->json();
            if (! is_array($data)) return $this->relay($upstream);
            $householdId = (int) $request->session()->get('household_id');
            $allowedIds = Child::query()->where('household_id', $householdId)->pluck('id')->map(fn ($id) => (int) $id)->all();
            $children = array_values(array_filter($data['children'] ?? [], fn ($child) => in_array((int) ($child['id'] ?? 0), $allowedIds, true)));
            $data['children'] = $children;
            $data['summary'] = [
                'total_exercises' => array_sum(array_map(fn ($child) => (int) ($child['today_count'] ?? 0), $children)),
                'active_today' => count(array_filter($children, fn ($child) => (int) ($child['today_count'] ?? 0) > 0)),
                'needs_attention' => array_values(array_map(
                    fn ($child) => (string) ($child['name'] ?? ''),
                    array_filter($children, fn ($child) => (bool) ($child['needs_attention'] ?? false))
                )),
            ];

            return response()->json($data);
        } catch (ConnectionException) {
            return response()->json(['message' => 'Le service Mama Judi est momentanément indisponible.'], 502);
        }
    }

    public function subjects(int $levelId): Response
    {
        return $this->get('/mama/subjects/'.$levelId);
    }

    public function blackboard(Request $request): Response
    {
        return $this->get('/mama/blackboard', $request->only(['q', 'level_id']));
    }

    public function createEveningSession(Request $request): Response
    {
        return $this->post('/evening-sessions', $request->all());
    }

    public function books(Request $request): Response { return $this->get('/books', $request->only(['level_id'])); }
    public function createBook(Request $request): Response { return $this->post('/books', $request->all()); }
    public function deleteBook(int $bookId): Response { return $this->delete('/books/'.$bookId); }
    public function bookForExercise(int $exerciseId): Response { return $this->get('/books/exercise/'.$exerciseId); }
    public function createDuel(Request $request): Response { return $this->post('/duels', $request->all()); }
    public function pendingDuel(int $childId): Response { return $this->get('/duels/pending/'.$childId); }
    public function startDuel(int $duelId): Response { return $this->post('/duels/'.$duelId.'/start'); }
    public function submitDuelResult(Request $request, int $duelId): Response { return $this->post('/duels/'.$duelId.'/result', $request->all()); }
    public function duelResults(int $duelId): Response { return $this->get('/duels/'.$duelId.'/results'); }
    public function duelExercises(Request $request): Response { return $this->get('/exercises/duel', $request->only(['ids'])); }
    public function pendingEveningSession(int $childId): Response { return $this->get('/evening-sessions/pending/'.$childId); }
    public function finishEveningSession(int $sessionId): Response { return $this->post('/evening-sessions/'.$sessionId.'/done'); }
    public function schedulerConfig(): Response { return $this->get('/evening-sessions/scheduler-config'); }
    public function updateSchedulerConfig(Request $request): Response { return $this->post('/evening-sessions/scheduler-config', $request->all()); }
    public function triggerAutoRevision(): Response { return $this->post('/evening-sessions/trigger-auto'); }
    public function dictionary(Request $request): Response { return $this->get('/revision/dictionary', $request->only(['q', 'level_id'])); }

    private function post(string $path, array $payload = []): Response
    {
        try {
            $upstream = Http::acceptJson()->timeout(20)->post(self::BASE_URL.$path, $payload);

            return $this->relay($upstream);
        } catch (ConnectionException) {
            return response()->json(['message' => 'Le service Mama Judi est momentanément indisponible.'], 502);
        }
    }

    private function delete(string $path): Response
    {
        try {
            return $this->relay(Http::acceptJson()->timeout(20)->delete(self::BASE_URL.$path));
        } catch (ConnectionException) {
            return response()->json(['message' => 'Le service Mama Judi est momentanément indisponible.'], 502);
        }
    }

    private function get(string $path, array $query = []): Response
    {
        try {
            $upstream = Http::acceptJson()
                ->timeout(15)
                ->get(self::BASE_URL.$path, $query);

            return $this->relay($upstream);
        } catch (ConnectionException) {
            return response()->json(['message' => 'Le service Mama Judi est momentanément indisponible.'], 502);
        }
    }

    private function relay(ClientResponse $upstream): Response
    {
        return response($upstream->body(), $upstream->status())
            ->header('Content-Type', $upstream->header('Content-Type') ?: 'application/json');
    }
}

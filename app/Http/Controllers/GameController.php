<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GameController extends Controller
{
    /**
     * Display the game ID checker form.
     */
    public function index()
    {
        return view('game.check');
    }

    /**
     * Check player ID and fetch nickname.
     */
    public function checkId(Request $request)
    {
        $game = $request->get('game');
        $id = $request->get('id');
        $zoneid = $request->get('zoneid');
        $user = auth()->user(); // Get the authenticated user object
        $today = date('Y-m-d');
        $hitsKey = "user_hits:{$user->id}:{$today}";
        $currentHits = Cache::get($hitsKey, 0);
        $dailyLimit = $user->getDailyLimit(); // Get daily limit from user object

        if ($currentHits >= $dailyLimit) {
            return response()->json([
                'status' => 'error',
                'message' => "Daily limit reached (Max $dailyLimit checks per day)."
            ], 429);
        }

        // Increment hit counter for every request, regardless of cache hit or miss
        if (Cache::has($hitsKey)) {
            Cache::increment($hitsKey);
        } else {
            Cache::put($hitsKey, 1, now()->endOfDay());
        }

        $cacheKey = 'check_id_' . $game . '_' . $id . '_' . $zoneid;

        $result = Cache::remember($cacheKey, 600, function () use ($game, $id, $zoneid) {
            $externalApiKey = "c9b26e7b2b1ce0c00cfbaab1231c8fb4275ce08458840a1c5c";

            $payload = [
                "game" => $game,
                "id" => $id,
                "apikey" => $externalApiKey
            ];

            if ($game === 'ml') {
                $payload['zoneid'] = $zoneid;
            }

            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.velixs.com/idgames-checker',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($payload),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return ['error' => true, 'message' => 'Connection error: ' . $err];
                }

                $data = json_decode($response, true);

                if (isset($data['data'])) {
                    $playerData = $data['data'];
                    if (is_array($playerData)) {
                        $username = $playerData['username'] ?? $playerData['name'] ?? $playerData['nickname'] ?? null;
                        if ($username) {
                            return ['error' => false, 'username' => $username];
                        }
                    } elseif (is_string($playerData) && $playerData !== 'error') {
                        return ['error' => false, 'username' => $playerData];
                    }
                }

                return ['error' => true, 'message' => 'Player not found'];
            } catch (\Exception $e) {
                return ['error' => true, 'message' => 'Server error: ' . $e->getMessage()];
            }
        });

        // Fix for old cached objects (JsonResponse)
        if ($result instanceof \Illuminate\Http\JsonResponse) {
            $data = $result->getData(true);
            $username = $data['data'] ?? ($data['username'] ?? null);
            $result = [
                'error' => ($username === 'error' || !$username),
                'username' => $username,
                'message' => $data['message'] ?? 'Player not found'
            ];
        }

        $hits = Cache::get($hitsKey, 0);
        $remaining = 50 - $hits;

        if ($result['error']) {
            return response()->json([
                'data' => 'error',
                'message' => $result['message'],
                'hits' => $hits,
                'remaining' => $remaining
            ], isset($result['message']) && str_contains($result['message'], 'Connection') ? 500 : 200);
        }

        return response()->json([
            'data' => $result['username'],
            'hits' => $hits,
            'remaining' => $remaining
        ]);
    }

    public function downloadTemplate()
    {
        $filePath = public_path('ID GAME CHECKER.postman_collection.json');
        return response()->download($filePath);
    }

    public function pricing()
    {
        return view('pricing');
    }

    public function apiTester()
    {
        $games = [
            ['code' => 'ml', 'name' => 'Mobile Legends'],
            ['code' => 'freefire', 'name' => 'Free Fire'],
            ['code' => 'codm', 'name' => 'Call of Duty Mobile'],
            ['code' => 'genshin', 'name' => 'Genshin Impact'],
            ['code' => 'aov', 'name' => 'Arena of Valor']
        ];

        return view('api-tester', compact('games'));
    }
}

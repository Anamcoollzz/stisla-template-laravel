<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class GameApiController extends Controller
{
    /**
     * List all supported games.
     */
    public function listGames()
    {
        $games = [
            ['code' => 'ml', 'name' => 'Mobile Legends'],
            ['code' => 'freefire', 'name' => 'Free Fire'],
            ['code' => 'codm', 'name' => 'Call of Duty Mobile'],
            ['code' => 'genshin', 'name' => 'Genshin Impact'],
            ['code' => 'aov', 'name' => 'Arena of Valor']
        ];

        return response()->json([
            'status' => 'success',
            'data' => $games
        ]);
    }

    /**
     * Check player ID and fetch nickname via API.
     */
    public function check(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'game' => 'required|string',
            'id' => 'required|string',
            'zoneid' => 'required_if:game,ml|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $game = $request->get('game');
        $id = $request->get('id');
        $zoneid = $request->get('zoneid');

        // Get user from middleware
        $user = $request->get('api_user');
        $userId = $user->id;
        $today = date('Y-m-d');
        $hitsKey = "user_hits:{$userId}:{$today}";

        $currentHits = Cache::get($hitsKey, 0);
        $dailyLimit = $user->getDailyLimit();

        if ($currentHits >= $dailyLimit) {
            return response()->json([
                'status' => 'error',
                'message' => "Daily limit reached (Max $dailyLimit checks per day)."
            ], 429);
        }

        // Increment hit counter for every request
        if (Cache::has($hitsKey)) {
            Cache::increment($hitsKey);
        } else {
            Cache::put($hitsKey, 1, now()->endOfDay());
        }

        $cacheKey = 'api_check_id_' . $game . '_' . $id . '_' . $zoneid;

        $result = Cache::remember($cacheKey, 600, function () use ($game, $id, $zoneid) {
            // Using the same API key logic as GameController
            $externalApiKey = "c9b26e7b2b1ce0c00cfbaab1231c8fb4275ce08458840a1c5c";

            $gameCode = $game;
            if ($game === 'mobile_legends') {
                $gameCode = 'ml';
            }

            $payload = [
                "game" => $gameCode,
                "id" => $id,
                "apikey" => $externalApiKey
            ];

            if ($gameCode === 'ml') {
                $payload['zoneid'] = $zoneid;
            }

            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.velixs.com/idgames-checker',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30, // Added timeout
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
                    return ['error' => true, 'message' => 'Connection error: ' . $err, 'code' => 500];
                }

                $data = json_decode($response, true);

                if (isset($data['data'])) {
                    $playerData = $data['data'];
                    $username = null;

                    if (is_array($playerData)) {
                        $username = $playerData['username'] ?? $playerData['name'] ?? $playerData['nickname'] ?? null;
                    } elseif (is_string($playerData) && $playerData !== 'error') {
                        $username = $playerData;
                    }

                    if ($username) {
                        return [
                            'error' => false,
                            'username' => $username,
                            'game' => $game,
                            'id' => $id
                        ];
                    }
                }

                return ['error' => true, 'message' => 'Player not found or invalid response from provider', 'code' => 404];
            } catch (\Exception $e) {
                return ['error' => true, 'message' => 'Server error: ' . $e->getMessage(), 'code' => 500];
            }
        });

        // Fix for old cached objects (JsonResponse)
        if ($result instanceof \Illuminate\Http\JsonResponse) {
            $data = $result->getData(true);
            $username = $data['data']['username'] ?? ($data['username'] ?? null);
            $result = [
                'error' => ($data['status'] ?? '') === 'error' || !$username,
                'username' => $username,
                'game' => $data['data']['game'] ?? $game,
                'id' => $data['data']['id'] ?? $id,
                'message' => $data['message'] ?? 'Player not found',
                'code' => $result->getStatusCode()
            ];
        }

        $hits = Cache::get($hitsKey, 0);
        $remaining = 50 - $hits;

        if ($result['error']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
                'hits' => $hits,
                'remaining' => $remaining
            ], $result['code'] ?? 200);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'game' => $result['game'],
                'id' => $result['id'],
                'username' => $result['username']
            ],
            'hits' => $hits,
            'remaining' => $remaining
        ]);
    }
}

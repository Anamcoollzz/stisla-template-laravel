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
            ['code' => 'mobile_legends', 'name' => 'Mobile Legends'],
            ['code' => 'freefire', 'name' => 'Free Fire'],
            ['code' => 'codm', 'name' => 'Call of Duty Mobile'],
            ['code' => 'genshin', 'name' => 'Genshin Impact'],
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

        $cacheKey = 'api_check_id_' . $game . '_' . $id . '_' . $zoneid;

        return Cache::remember($cacheKey, 600, function () use ($game, $id, $zoneid) {
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
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Connection error: ' . $err
                    ], 500);
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
                        return response()->json([
                            'status' => 'success',
                            'data' => [
                                'game' => $game,
                                'id' => $id,
                                'username' => $username
                            ]
                        ]);
                    }
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Player not found or invalid response from provider'
                ], 404);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Server error: ' . $e->getMessage()
                ], 500);
            }
        });
    }
}

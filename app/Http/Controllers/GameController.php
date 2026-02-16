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

        $cacheKey = 'check_id_' . $game . '_' . $id . '_' . $zoneid;

        return Cache::remember($cacheKey, 600, function () use ($game, $id, $zoneid) {
            $apiKey = "c9b26e7b2b1ce0c00cfbaab1231c8fb4275ce08458840a1c5c";

            $payload = [
                "game" => $game,
                "id" => $id,
                "apikey" => $apiKey
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
                    CURLOPT_TIMEOUT => 0,
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
                    return response()->json(['data' => 'error', 'message' => $err], 500);
                }

                $data = json_decode($response, true);

                // Handle different response formats
                if (isset($data['data'])) {
                    $playerData = $data['data'];

                    // If data is an array/object, try to extract username
                    if (is_array($playerData)) {
                        $username = $playerData['username'] ?? $playerData['name'] ?? $playerData['nickname'] ?? null;

                        if ($username) {
                            return response()->json(['data' => $username]);
                        }
                    } elseif (is_string($playerData) && $playerData !== 'error') {
                        // If data is already a string, return it
                        return response()->json(['data' => $playerData]);
                    }
                }

                return response()->json([
                    'data' => 'error',
                    'message' => 'Player not found or invalid response format'
                ]);
            } catch (\Exception $e) {
                return response()->json(['data' => 'error', 'message' => $e->getMessage()], 500);
            }
        });
    }
}

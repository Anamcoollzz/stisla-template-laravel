<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY') ?? $request->get('api_key');

        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key is required.'
            ], 401);
        }

        $user = User::where('api_key', $apiKey)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid API Key.'
            ], 401);
        }

        // Add user to request so it can be accessed in controller
        $request->merge(['api_user' => $user]);

        return $next($request);
    }
}

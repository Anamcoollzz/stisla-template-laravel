<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    public function index()
    {
        return view('api-key.index', [
            'user' => Auth::user()
        ]);
    }

    public function generate(Request $request)
    {
        $user = Auth::user();

        // Generate a 32-character random string for the API key
        $apiKey = Str::random(32);

        $user->update([
            'api_key' => $apiKey
        ]);

        return back()->with('success', 'API Key generated successfully!');
    }

    public function regenerate(Request $request)
    {
        $user = Auth::user();

        $apiKey = Str::random(32);

        $user->update([
            'api_key' => $apiKey
        ]);

        return back()->with('success', 'API Key regenerated successfully!');
    }
}

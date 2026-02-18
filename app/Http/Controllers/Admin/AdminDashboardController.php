<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'pro_users' => User::where('plan', 'pro')->count(),
            'free_users' => User::where('plan', 'free')->orWhereNull('plan')->count(),
        ];

        return view('admin.admin-dashboard', compact('stats'));
    }
}

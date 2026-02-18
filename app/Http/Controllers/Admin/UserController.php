<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Cancel a user's subscription.
     */
    public function cancelSubscription(User $user)
    {
        // Don't let admin cancel their own if they have it, 
        // but primarily we want to reset plan to free.
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot cancel your own subscription status here.');
        }

        $user->update([
            'plan' => 'free',
            'plan_expires_at' => null
        ]);

        return back()->with('success', "Subscription for {$user->name} has been cancelled successfully.");
    }
}

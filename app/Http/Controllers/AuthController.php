<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Handle login logic here
    }

    public function logout(Request $request)
    {
        // Handle logout logic here
    }

    public function formRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Handle registration logic here
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function datatable()
    {
        return view('admin.datatable');
    }
}

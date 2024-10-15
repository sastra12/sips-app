<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authView()
    {
        return view('auth.auth');
    }

    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            // acces route dashboard
            return redirect()->intended('dashboard-new');
        }
        return redirect()->route('login')->with('status', 'Login Gagal, Username/Password keliru');
    }

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

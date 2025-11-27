<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember_me' => ['boolean'],
        ]);

        // Attempt to authenticate
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember_me');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->isActive()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Your account has been deactivated. Please contact support.'],
                ]);
            }
            
            // Check if user has admin or editor role
            if (!$user->isEditor()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['You do not have permission to access the admin panel.'],
                ]);
            }
            
            // Update last login timestamp
            $user->updateLastLogin();
            
            return redirect()->intended('/admin/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/admin/login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm(Request $request)
    {
        $role = $request->query('role', '');
        return view('auth.login', compact('role'));
    }

    /**
     * Handle the login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user role matches intended role
            $intendedRole = $request->input('role');
            /** @var User $user */
            $user = Auth::user();
            $userRole = $user->role;
            
            if (!empty($intendedRole) && $intendedRole !== $userRole) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'role' => "Your account is registered as a {$userRole}. Please use the appropriate login option.",
                ])->withInput();
            }

            // Redirect based on user role
            if ($user->isInstructor()) {
                return redirect()->intended(route('instructor.dashboard'));
            }
            
            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}

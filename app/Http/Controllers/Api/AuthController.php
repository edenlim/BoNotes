<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): JsonResponse
    {
        // 1. Validate the incoming payload
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Attempt to authenticate using the 'web' guard
        if (Auth::attempt($credentials)) {

            // 3. CRITICAL: Prevent session fixation & issue the authorized cookie
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Logged in successfully',
                'user'    => Auth::user()
            ]);
        }

        // 4. Return standard 401 if credentials do not match
        return response()->json([
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): JsonResponse
    {
        // 1. Log the user out of the backend guard
        Auth::guard('web')->logout();

        // 2. Invalidate the current session cookie
        $request->session()->invalidate();

        // 3. Regenerate the CSRF token for future security
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        // Fetches all rows from the users table via MariaDB
        $users = User::all();

        return response()->json($users);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if ($user->id !== $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Das aktuelle Passwort ist falsch.'], 403);
        }

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($request->filled('password')) {
            Auth::login($user);
        }

        return response()->json(['message' => 'Profil erfolgreich aktualisiert.', 'user' => $user]);
    }
}

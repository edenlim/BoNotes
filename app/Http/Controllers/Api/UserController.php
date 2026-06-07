<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        // Fetches all rows from the users table via MariaDB
        $users = User::all();

        return response()->json($users);
    }
}

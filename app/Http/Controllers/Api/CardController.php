<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Http\JsonResponse;

class CardController extends Controller
{
    public function index(): JsonResponse
    {
        // Fetches all rows from the cards table via MariaDB
        $cards = Card::all();

        return response()->json($cards);
    }
}

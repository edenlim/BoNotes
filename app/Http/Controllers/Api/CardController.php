<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        $query = Card::query();

        if ($user) {
            Log::debug('from user');

            $query->leftJoin('users_cards_like', function ($join) use ($user) {
                $join->on('cards.id', '=', 'users_cards_like.card_id')
                    ->where('users_cards_like.user_id', '=', $user->id);
            })
                ->select(
                    'cards.*',
                    DB::raw("IFNULL(users_cards_like.status, 'none') as interaction_status")
                );
        } else {
            Log::debug('from else');

            $query->select(
                'cards.*',
                DB::raw("'none' as interaction_status")
            );
        }

        $cards = $query->get();

        return response()->json($cards);
    }
}

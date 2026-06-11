<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\UserCardRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        $query = Card::with('user:id,name');

        if ($user) {
            $query->leftJoin('users_cards_ratings', function ($join) use ($user) {
                $join->on('cards.id', '=', 'users_cards_ratings.card_id')
                    ->where('users_cards_ratings.user_id', '=', $user->id);
            })
                ->select(
                    'cards.*',
                    DB::raw("IFNULL(users_cards_ratings.status, 'none') as interaction_status")
                );
        } else {
            $query->select(
                'cards.*',
                DB::raw("'none' as interaction_status")
            );
        }

        $cards = $query->get();

        return response()->json($cards);
    }

    public function rate(Request $request, Card $card): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:liked,disliked,none'],
        ]);
        $newStatus = $request->status;
        $user = $request->user('sanctum');
        $updatedCard = DB::transaction(function () use ($card, $user, $newStatus) {
            $lockedCard = Card::lockForUpdate()->findOrFail($card->id);
            $userRating = UserCardRating::firstOrNew([
                'user_id' => $user->id,
                'card_id' => $lockedCard->id,
            ]);
            $oldStatus = $userRating->exists ? $userRating->status : 'none';

            if ($oldStatus === $newStatus) {
                return $lockedCard;
            }

            if ($oldStatus === 'liked') {
                $lockedCard->noOfLikes = max(0, $lockedCard->noOfLikes - 1);
            } elseif ($oldStatus === 'disliked') {
                $lockedCard->noOfDislikes = max(0, $lockedCard->noOfDislikes - 1);
            }
            if ($newStatus === 'liked') {
                $lockedCard->noOfLikes++;
            } elseif ($newStatus === 'disliked') {
                $lockedCard->noOfDislikes++;
            }
            $userRating->status = $newStatus;
            $userRating->save();
            $lockedCard->save();
            return $lockedCard;

        });
        return response()->json([
            'noOfLikes'          => $updatedCard->noOfLikes,
            'noOfDislikes'       => $updatedCard->noOfDislikes,
            'interaction_status' => $newStatus,
        ]);
    }
}

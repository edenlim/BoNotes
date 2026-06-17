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

    public function infiniteLoad(Request $request, int $lastIndex): JsonResponse
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

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where('cards.title', 'like', '%' . $search . '%');
        }

        if ($request->filled('tags')) {
            $selectedTags = array_map(function ($tag) {
                return strtolower(trim($tag));
            }, explode(',', $request->query('tags')));

            $allTags = ['mathe', 'informatik', 'wirtschaft', 'maschinenbau'];
            $unselectedTags = array_diff($allTags, $selectedTags);

            $query->whereNotNull('cards.tags')
                  ->where('cards.tags', '!=', '[]')
                  ->where('cards.tags', '!=', '');

            foreach ($unselectedTags as $unselectedTag) {
                $query->whereJsonDoesntContain('cards.tags', $unselectedTag);
            }
        }

        // Sort by newest first, skip the already loaded records, and take 20
        $cards = $query->orderBy('cards.created_at', 'desc')
            ->orderBy('cards.id', 'desc')
            ->skip($lastIndex)
            ->take(20)
            ->get();
        return response()->json($cards);
    }
    public function update(Request $request, Card $card): JsonResponse
    {
        // Nur der Eigentümer darf updaten
        $user = $request->user('sanctum');
        if ($card->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'nullable'],
        ]);

        $card->update($validated);

        return response()->json($card);
    }

    public function show(Request $request, Card $card): JsonResponse
    {
        $user = $request->user('sanctum');

        $query = Card::with('user:id,name')->where('cards.id', $card->id);

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

        $result = $query->firstOrFail();

        return response()->json($result);
    }
}

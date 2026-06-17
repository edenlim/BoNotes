<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\UserCardRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file'        => ['required', 'file', 'mimes:txt,pdf', 'max:10240'], // max 10 MB
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'tags'        => ['nullable', 'string'], // comma-separated tags from frontend
        ]);

        $user      = $request->user();
        $file      = $request->file('file');
        $storedPath = null;

        try {
            $card = DB::transaction(function () use ($user, $file, $request, &$storedPath) {
                // 1. Store the file inside the transaction callback so any exception rolls back the DB record.
                //    We keep $storedPath outside so the catch block can clean up the file.
                $storedPath = $file->store('notes', 'public');

                if ($storedPath === false) {
                    throw new \RuntimeException('File could not be stored.');
                }

                // 2. Parse tags: frontend sends a JSON array string or a comma-separated string
                $rawTags = $request->input('tags', '[]');
                $tags = is_string($rawTags) ? json_decode($rawTags, true) : $rawTags;
                if (!is_array($tags)) {
                    $tags = array_filter(array_map('trim', explode(',', $rawTags)));
                }

                // 3. Determine page length (line count for TXT, 0 for others)
                $extension  = strtolower($file->getClientOriginalExtension());
                $pageLength = 0;
                if ($extension === 'txt') {
                    $pageLength = substr_count(file_get_contents($file->getRealPath()), "\n") + 1;
                }

                // 4. Create the DB record inside the transaction
                return Card::create([
                    'user_id'     => $user->id,
                    'title'       => $request->input('title'),
                    'description' => $request->input('description'),
                    'fileType'    => '.' . strtolower($extension), // Macht aus "pdf" => ".pdf"
                    'tags'        => $tags,
                    'file_path'   => $storedPath,
                    'upload_time' => now()->toDateTimeString(),
                    'page_length' => $pageLength,
                    'noOfLikes'   => 0,
                    'noOfDislikes'=> 0,
                ]);
            });

            // 5. Load the user relationship so the frontend gets the author name
            $card->load('user:id,name');
            $card->file_url = Storage::disk('public')->url($card->file_path);
            $card->interaction_status = 'none';

            return response()->json($card, 201);

        } catch (\Throwable $e) {
            // 6. Rollback: delete the file if it was already stored but the DB write failed
            if ($storedPath !== null && Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->delete($storedPath);
                Log::warning('Upload rolled back – orphan file deleted', ['path' => $storedPath]);
            }

            Log::error('Card upload failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Upload fehlgeschlagen. Bitte versuche es erneut.',
            ], 500);
        }
    }

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

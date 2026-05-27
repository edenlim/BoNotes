<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CardSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('mock/cards.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("Cards mock file not found.");
            return;
        }

        $cards = json_decode(File::get($jsonPath), true);

        foreach ($cards as $card) {
            Card::create([
                'title'          => $card['title'],
                'file_type'      => $card['fileType'],
                'tags'           => $card['tags'],
                'user_vote'      => $card['userVote'] ?? null,
                'no_of_likes'    => $card['noOfLikes'] ?? $card['PeteofLikes'] ?? 0,
                'no_of_dislikes' => $card['noOfDislikes'] ?? 0,
                'author'         => $card['author'],
                'upload_time'    => $card['uploadTime'],
                'page_length'    => $card['pageLength'],
                'description'    => $card['description'],
            ]);
        }
    }
}

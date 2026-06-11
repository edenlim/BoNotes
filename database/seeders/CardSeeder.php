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

        $rawJsonData = File::get($jsonPath);
        $parsedCards = json_decode($rawJsonData, true);

        foreach ($parsedCards as $card) {
            Card::create([
                'title'          => $card['title'],
                'fileType'      => $card['fileType'],
                'tags'           => $card['tags'],
                'noOfLikes'    => $card['noOfLikes'] ?? 0,
                'noOfDislikes' => $card['noOfDislikes'] ?? 0,
                'user_id'         => $card['user_id'],
                'upload_time'  => $card['uploadTime'],
                'page_length'  => $card['pageLength'],
                'description'    => $card['description'],
            ]);
        }
    }
}

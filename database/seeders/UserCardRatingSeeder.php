<?php

namespace Database\Seeders;

use App\Models\UserCardRating;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UserCardRatingSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = public_path('mock/users_cards_ratings.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("Users_cards_ratings mock file not found.");
            return;
        }

        $cards = json_decode(File::get($jsonPath), true);

        foreach ($cards as $card) {
            UserCardRating::create([
                'user_id' => $card['user_id'],
                'card_id' => $card['card_id'],
                'status'  => $card['status'],
            ]);
        }
    }
}

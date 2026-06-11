<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCardLike extends Model
{
    protected $table = 'users_cards_like';

    // Unlocks mass insertion privileges for your seeder entries
    protected $fillable = [
        'user_id',
        'card_id',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCardRating extends Model
{
    protected $table = 'users_cards_ratings';

    protected $fillable = [
        'user_id',
        'card_id',
        'status',
    ];
}

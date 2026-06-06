<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'title', 'fileType', 'tags', 'user_vote',
        'no_of_likes', 'no_of_dislikes', 'author',
        'upload_time', 'page_length', 'description'
    ];

    protected $casts = [
        'tags' => 'array',
    ];
}

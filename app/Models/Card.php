<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'title', 'fileType', 'tags', 'user_vote',
        'noOfLikes', 'noOfDislikes', 'author',
        'upload_time', 'page_length', 'description'
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }
}

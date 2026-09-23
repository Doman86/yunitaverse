<?php

namespace App\Models;

class Favorite extends Content
{
    protected $table = 'favorites';

    public const CATEGORIES = [
        'music' => 'Music',
        'food' => 'Food',
        'movies' => 'Movies',
        'books' => 'Books',
        'places' => 'Places',
        'hobbies' => 'Hobbies',
        'things' => 'Things She Likes',
    ];
}

<?php

namespace App\Models;

class Favorite extends Content
{
    protected $table = 'favorites';

    /**
     * Category => admin.* translation key, resolved per active locale.
     */
    public const CATEGORIES = [
        'music' => 'cat_music',
        'food' => 'cat_food',
        'movies' => 'cat_movies',
        'books' => 'cat_books',
        'places' => 'cat_places',
        'hobbies' => 'cat_hobbies',
        'things' => 'cat_things',
    ];
}

<?php

namespace App\Models;

class Journey extends Content
{
    protected $table = 'journeys';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'year' => 'integer',
        ]);
    }
}

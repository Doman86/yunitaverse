<?php

namespace App\Models;

class ModThingToDo extends Content
{
    protected $table = 'mod_things_to_do';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'order' => 'integer',
        ]);
    }
}

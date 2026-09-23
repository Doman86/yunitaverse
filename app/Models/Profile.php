<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = [
        'name',
        'title',
        'bio',
        'about',
        'favorites',
        'quote',
        'photo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'favorites' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function photoUrl(): string
    {
        return $this->photo ? Storage::url($this->photo) : '';
    }
}

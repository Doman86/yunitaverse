<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = ['admin', 'yunita'];

    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function moments(): HasMany
    {
        return $this->hasMany(Moment::class, 'created_by');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'created_by');
    }

    public function memories(): HasMany
    {
        return $this->hasMany(Memory::class, 'created_by');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'created_by');
    }

    public function journeys(): HasMany
    {
        return $this->hasMany(Journey::class, 'created_by');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'created_by');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'created_by');
    }

    public function modPhotos(): HasMany
    {
        return $this->hasMany(ModPhoto::class, 'created_by');
    }

    public function modPlaylists(): HasMany
    {
        return $this->hasMany(ModPlaylist::class, 'created_by');
    }

    public function modSurprises(): HasMany
    {
        return $this->hasMany(ModSurprise::class, 'created_by');
    }

    public function modNotes(): HasMany
    {
        return $this->hasMany(ModNote::class, 'created_by');
    }

    public function modThingsToDo(): HasMany
    {
        return $this->hasMany(ModThingToDo::class, 'created_by');
    }
}

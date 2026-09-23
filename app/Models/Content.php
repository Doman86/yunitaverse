<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class Content extends Model
{
    protected $fillable = [
        'title',
        'caption',
        'description',
        'content',
        'image',
        'link',
        'date',
        'year',
        'category',
        'mood',
        'order',
        'kind',
        'type',
        'spotify_url',
        'created_by',
        'status',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Content visible on the public website.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Content owned by a specific user (ownership validation).
     */
    public function scopeOwnedBy(Builder $query, User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}

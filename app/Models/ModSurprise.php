<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ModSurprise extends Content
{
    protected $table = 'mod_surprises';

    public const TYPES = ['photo', 'note', 'quote', 'activity', 'music', 'memory'];

    /**
     * Draw one random surprise, preferring a mood match then falling back to 'all'.
     */
    public static function draw(?string $mood): ?self
    {
        if (! in_array($mood, ['good', 'normal', 'sad'], true)) {
            $mood = null;
        }

        return static::query()
            ->published()
            ->when($mood, fn (Builder $q) => $q->where(function (Builder $q) use ($mood) {
                $q->where('mood', $mood)->orWhere('mood', 'all');
            }), fn (Builder $q) => $q->where('mood', 'all'))
            ->inRandomOrder()
            ->first();
    }
}

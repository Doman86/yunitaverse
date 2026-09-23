<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        $settings = Cache::rememberForever('settings.all', fn () => static::query()->pluck('value', 'key')->all());

        $value = $settings[$key] ?? null;

        return $value !== null && $value !== '' ? $value : $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);

        Cache::forget('settings.all');
    }
}

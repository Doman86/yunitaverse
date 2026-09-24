<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Resolves every visitor-facing sentence for the active locale.
 *
 * Resolution order:
 *   1. DB "text.{locale}.{key}"      — what the admin typed for this language
 *   2. lang/{locale}/text.php {key}  — built-in default for this language
 *   3. DB "text.{key}"               — legacy value written before i18n
 *   4. lang/en/text.php {key}        — final safety net
 */
class Text
{
    public static function get(string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return Setting::get("text.{$locale}.{$key}")
            ?? __("text.{$key}", [], $locale)
            ?? Setting::get("text.{$key}")
            ?? __("text.{$key}", [], config('app.fallback_locale', 'en'));
    }
}

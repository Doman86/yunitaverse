<?php

namespace App\View\Composers;

use App\Models\Setting;
use App\Support\Text;
use Illuminate\View\View;

class SiteComposer
{
    private const ACCENTS = [
        'moon' => [
            'button' => 'bg-stone-200 text-stone-900 hover:bg-white focus-visible:outline-stone-200',
            'glow' => 'shadow-stone-500/20',
            'soft' => 'bg-stone-800/80 text-stone-200 border border-stone-700',
            'text' => 'text-amber-200/80',
            'border' => 'border-stone-800',
        ],
        'rose' => [
            'button' => 'bg-rose-300/90 text-stone-900 hover:bg-rose-300 focus-visible:outline-rose-300',
            'glow' => 'shadow-rose-400/20',
            'soft' => 'bg-rose-400/10 text-rose-200/90 border border-rose-400/20',
            'text' => 'text-rose-200/80',
            'border' => 'border-rose-400/20',
        ],
        'amber' => [
            'button' => 'bg-amber-300/90 text-stone-900 hover:bg-amber-300 focus-visible:outline-amber-300',
            'glow' => 'shadow-amber-400/20',
            'soft' => 'bg-amber-400/10 text-amber-200/90 border border-amber-400/20',
            'text' => 'text-amber-200/80',
            'border' => 'border-amber-400/20',
        ],
        'violet' => [
            'button' => 'bg-violet-300/90 text-stone-900 hover:bg-violet-300 focus-visible:outline-violet-300',
            'glow' => 'shadow-violet-400/20',
            'soft' => 'bg-violet-400/10 text-violet-200/90 border border-violet-400/20',
            'text' => 'text-violet-200/80',
            'border' => 'border-violet-400/20',
        ],
        'teal' => [
            'button' => 'bg-teal-300/90 text-stone-900 hover:bg-teal-300 focus-visible:outline-teal-300',
            'glow' => 'shadow-teal-400/20',
            'soft' => 'bg-teal-400/10 text-teal-200/90 border border-teal-400/20',
            'text' => 'text-teal-200/80',
            'border' => 'border-teal-400/20',
        ],
    ];

    /**
     * Every public-facing sentence resolves per locale:
     * admin DB value (text.{locale}.*) → lang file default → legacy DB value.
     */
    private const TEXT_KEYS = [
        // Landing & Home
        'landing_tagline',
        'enter_label',
        'home_intro',
        'archive_label',
        'archive_sub',
        'mod_label',
        'mod_sub',
        'soundtrack_label',
        'soundtrack_sub',
        'myspace_label',
        'myspace_sub',
        'footer_links_label',
        'footer_note',

        // Her Archive
        'archive_intro',
        'archive_label_profile',
        'archive_sub_profile',
        'archive_label_moments',
        'archive_sub_moments',
        'archive_label_journey',
        'archive_sub_journey',
        'archive_label_achievements',
        'archive_sub_achievements',
        'archive_label_activities',
        'archive_sub_activities',
        'archive_label_favorites',
        'archive_sub_favorites',

        // Empty states
        'empty_title',
        'empty_message',

        // MOD
        'mod_question',
        'mod_good_intro',
        'mod_normal_intro',
        'mod_sad_intro',
        'mod_things_label',
        'mod_notes_label',
        'mod_photos_label',
        'mod_music_label',
        'mod_calm_music_label',
        'mod_comfort_notes_label',
        'mod_little_things_label',
        'mod_surprise_label',
        'mod_surprise_again',
        'mod_surprise_empty',
        'mod_things_page_sub',
        'mod_notes_page_sub',
        'mod_photos_page_sub',
        'mod_music_page_sub',

        // Soundtrack
        'soundtrack_page_sub',
        'soundtrack_playlists_label',
        'soundtrack_tracks_label',
        'soundtrack_empty',

        // Auth & My Space
        'login_welcome',
        'login_back',
        'myspace_welcome',
        'myspace_intro',
        'myspace_add_moment',
        'myspace_add_note',
        'myspace_add_activity',
        'myspace_add_memory',
        'myspace_add_mod',
    ];

    public function compose(View $view): void
    {
        $accentKey = Setting::get('accent', 'moon');

        $text = collect(self::TEXT_KEYS)
            ->mapWithKeys(fn (string $key) => [$key => Text::get($key)]);

        $view->with([
            'siteName' => Setting::get('site_name', 'YUNITAVERSE'),
            'siteTagline' => Setting::get('site_tagline', Text::get('landing_tagline')),
            'timezone' => Setting::get('timezone', 'Asia/Jakarta'),
            'profileName' => Setting::get('profile_name', 'Yunita Dwi Alung'),
            'metaDescription' => Setting::get('meta_description', 'A little universe of Yunita Dwi Alung.'),
            'ogTitle' => Setting::get('og_title', 'YUNITAVERSE'),
            'ogDescription' => Setting::get('og_description', 'A little universe of Yunita Dwi Alung.'),
            'ogImage' => Setting::get('og_image'),
            'themeColor' => Setting::get('theme_color', '#0c0a09'),
            'accent' => self::ACCENTS[$accentKey] ?? self::ACCENTS['moon'],
            'text' => $text->all(),
        ]);
    }
}

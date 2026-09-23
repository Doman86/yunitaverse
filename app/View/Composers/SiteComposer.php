<?php

namespace App\View\Composers;

use App\Models\Setting;
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
     * Every public-facing copy lives in Settings so the admin can edit it
     * without touching code. Views must not hardcode sentences.
     */
    private const TEXT_DEFAULTS = [
        // Landing
        'landing_tagline' => 'a little universe of her world',
        'enter_label' => 'Enter',

        // Home hub
        'home_intro' => 'A little universe of :name.',
        'archive_label' => 'HER ARCHIVE',
        'archive_sub' => 'Discover her world.',
        'mod_label' => 'MOD',
        'mod_sub' => 'Choose what you need today.',
        'soundtrack_label' => 'SOUNDTRACK',
        'soundtrack_sub' => 'A collection of sounds.',
        'myspace_label' => 'MY SPACE',
        'myspace_sub' => 'Make your own little corner.',
        'footer_links_label' => 'Moments · Journey · Favorites',
        'footer_note' => 'made to be revisited.',

        // Archive
        'archive_intro' => 'Every part of the archive is free to wander.',
        'archive_label_profile' => 'Profile',
        'archive_sub_profile' => 'Who she is, in her own words.',
        'archive_label_moments' => 'Moments',
        'archive_sub_moments' => 'Little pieces of her world, kept safe here.',
        'archive_label_journey' => 'Journey',
        'archive_sub_journey' => 'The road so far, one little step at a time.',
        'archive_label_achievements' => 'Achievements',
        'archive_sub_achievements' => 'Quiet wins, kept in a row.',
        'archive_label_activities' => 'Activities',
        'archive_sub_activities' => 'What fills her days.',
        'archive_label_favorites' => 'Favorites',
        'archive_sub_favorites' => 'The things she keeps close.',

        // Empty states
        'empty_title' => 'Nothing here yet.',
        'empty_message' => 'Come back later.',

        // MOD
        'mod_question' => 'How are you today?',
        'mod_good_intro' => "Glad you're feeling good. What do you feel like doing?",
        'mod_normal_intro' => 'Maybe you just need a little something.',
        'mod_sad_intro' => 'Take your time. There is no hurry here.',
        'mod_things_label' => 'THINGS TO DO',
        'mod_notes_label' => 'LITTLE NOTES',
        'mod_photos_label' => 'PHOTOS',
        'mod_music_label' => 'MUSIC',
        'mod_calm_music_label' => 'CALM MUSIC',
        'mod_comfort_notes_label' => 'COMFORT NOTES',
        'mod_little_things_label' => 'LITTLE THINGS TO DO',
        'mod_surprise_label' => 'SURPRISE',
        'mod_surprise_again' => 'surprise me again',
        'mod_surprise_empty' => 'Nothing is hidden here yet. Come back later. ✦',
        'mod_things_page_sub' => 'Small steps for today.',
        'mod_notes_page_sub' => 'Little words, kept for you.',
        'mod_photos_page_sub' => 'Frames from her little universe.',
        'mod_music_page_sub' => 'Something to listen to.',

        // Soundtrack
        'soundtrack_page_sub' => 'Songs that belong somewhere here.',
        'soundtrack_playlists_label' => 'Playlists',
        'soundtrack_tracks_label' => 'Songs',
        'soundtrack_empty' => 'The soundtrack is still being written.',

        // Auth / My Space
        'login_welcome' => 'Welcome back',
        'login_back' => '← back to the universe',
        'myspace_welcome' => 'Welcome back, :name.',
        'myspace_intro' => 'This is your own little corner. Make something whenever you feel like it.',
        'myspace_add_moment' => '+ Add Moment',
        'myspace_add_note' => '+ Write Note',
        'myspace_add_activity' => '+ Add Activity',
        'myspace_add_memory' => '+ Add Memory',
        'myspace_add_mod' => '+ Add MOD Content',
    ];

    public function compose(View $view): void
    {
        $accentKey = Setting::get('accent', 'moon');

        $text = collect(self::TEXT_DEFAULTS)
            ->map(fn (string $default, string $key) => Setting::get("text.{$key}", $default));

        $view->with([
            'siteName' => Setting::get('site_name', 'YUNITAVERSE'),
            'siteTagline' => Setting::get('site_tagline', $text->get('landing_tagline')),
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

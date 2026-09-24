<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Site keys the admin can edit.
     */
    private const KEYS = [
        'site_name' => ['required', 'string', 'max:255'],
        'site_tagline' => ['nullable', 'string', 'max:500'],
        'timezone' => ['required', 'string', 'max:100'],
        'profile_name' => ['required', 'string', 'max:255'],
        'profile_bio' => ['nullable', 'string', 'max:2000'],
        'theme_color' => ['nullable', 'string', 'max:20'],
        'meta_description' => ['nullable', 'string', 'max:500'],
        'og_title' => ['nullable', 'string', 'max:255'],
        'og_description' => ['nullable', 'string', 'max:500'],
    ];

    /**
     * Every public-facing sentence, grouped for the settings page.
     * Each key is stored per locale as text.{locale}.{key}.
     * Labels/hints are translation keys so they follow the active locale.
     */
    private const TEXT_FIELDS = [
        'settings.section.landing_home' => [
            'landing_tagline' => ['settings.landing_tagline', 'settings.landing_tagline_hint'],
            'home_intro' => ['settings.home_intro', 'settings.home_intro_hint'],
            'archive_label' => ['settings.archive_label', 'settings.archive_label_hint'],
            'archive_sub' => ['settings.archive_sub', 'settings.archive_sub_hint'],
            'mod_label' => ['settings.mod_label', 'settings.mod_label_hint'],
            'mod_sub' => ['settings.mod_sub', ''],
            'soundtrack_label' => ['settings.soundtrack_label', 'settings.soundtrack_label_hint'],
            'soundtrack_sub' => ['settings.soundtrack_sub', ''],
            'myspace_label' => ['settings.myspace_label', 'settings.myspace_label_hint'],
            'myspace_sub' => ['settings.myspace_sub', ''],
            'footer_links_label' => ['settings.footer_links_label', 'settings.footer_links_label_hint'],
            'footer_note' => ['settings.footer_note', 'settings.footer_note_hint'],
        ],
        'settings.section.archive' => [
            'archive_intro' => ['settings.archive_intro', 'settings.archive_intro_hint'],
            'archive_label_profile' => ['settings.archive_label_profile', ''],
            'archive_sub_profile' => ['settings.archive_sub_profile', ''],
            'archive_label_moments' => ['settings.archive_label_moments', ''],
            'archive_sub_moments' => ['settings.archive_sub_moments', ''],
            'archive_label_journey' => ['settings.archive_label_journey', ''],
            'archive_sub_journey' => ['settings.archive_sub_journey', ''],
            'archive_label_achievements' => ['settings.archive_label_achievements', ''],
            'archive_sub_achievements' => ['settings.archive_sub_achievements', ''],
            'archive_label_activities' => ['settings.archive_label_activities', ''],
            'archive_sub_activities' => ['settings.archive_sub_activities', ''],
            'archive_label_favorites' => ['settings.archive_label_favorites', ''],
            'archive_sub_favorites' => ['settings.archive_sub_favorites', ''],
        ],
        'settings.section.mod' => [
            'mod_question' => ['settings.mod_question', 'settings.mod_question_hint'],
            'mod_good_intro' => ['settings.mod_good_intro', ''],
            'mod_normal_intro' => ['settings.mod_normal_intro', ''],
            'mod_sad_intro' => ['settings.mod_sad_intro', ''],
            'mod_things_label' => ['settings.mod_things_label', 'settings.mod_things_label_hint'],
            'mod_notes_label' => ['settings.mod_notes_label', ''],
            'mod_photos_label' => ['settings.mod_photos_label', ''],
            'mod_music_label' => ['settings.mod_music_label', ''],
            'mod_calm_music_label' => ['settings.mod_calm_music_label', 'settings.mod_calm_music_label_hint'],
            'mod_comfort_notes_label' => ['settings.mod_comfort_notes_label', 'settings.mod_comfort_notes_label_hint'],
            'mod_little_things_label' => ['settings.mod_little_things_label', 'settings.mod_little_things_label_hint'],
            'mod_surprise_label' => ['settings.mod_surprise_label', ''],
            'mod_surprise_again' => ['settings.mod_surprise_again', 'settings.mod_surprise_again_hint'],
            'mod_surprise_empty' => ['settings.mod_surprise_empty', 'settings.mod_surprise_empty_hint'],
            'mod_things_page_sub' => ['settings.mod_things_page_sub', ''],
            'mod_notes_page_sub' => ['settings.mod_notes_page_sub', ''],
            'mod_photos_page_sub' => ['settings.mod_photos_page_sub', ''],
            'mod_music_page_sub' => ['settings.mod_music_page_sub', ''],
        ],
        'settings.section.soundtrack' => [
            'soundtrack_page_sub' => ['settings.soundtrack_page_sub', ''],
            'soundtrack_playlists_label' => ['settings.soundtrack_playlists_label', ''],
            'soundtrack_tracks_label' => ['settings.soundtrack_tracks_label', ''],
            'soundtrack_empty' => ['settings.soundtrack_empty', 'settings.soundtrack_empty_hint'],
            'empty_title' => ['settings.empty_title', 'settings.empty_title_hint'],
            'empty_message' => ['settings.empty_message', 'settings.empty_message_hint'],
        ],
        'settings.section.login_myspace' => [
            'login_welcome' => ['settings.login_welcome', ''],
            'login_back' => ['settings.login_back', ''],
            'myspace_welcome' => ['settings.myspace_welcome', 'settings.myspace_welcome_hint'],
            'myspace_intro' => ['settings.myspace_intro', ''],
            'myspace_add_moment' => ['settings.myspace_add_moment', ''],
            'myspace_add_note' => ['settings.myspace_add_note', ''],
            'myspace_add_activity' => ['settings.myspace_add_activity', ''],
            'myspace_add_memory' => ['settings.myspace_add_memory', ''],
            'myspace_add_mod' => ['settings.myspace_add_mod', ''],
        ],
    ];

    private const LOCALES = ['id', 'en'];

    public function edit(): View
    {
        $textKeys = collect(self::TEXT_FIELDS)
            ->flatMap(fn (array $fields) => array_keys($fields))
            ->all();

        return view('manage.settings', [
            'settings' => collect(self::KEYS)->mapWithKeys(fn (array $rule, string $key) => [
                $key => Setting::get($key, ''),
            ]),
            'texts' => collect(self::LOCALES)
                ->mapWithKeys(fn (string $locale) => [
                    $locale => collect($textKeys)->mapWithKeys(fn (string $key) => [
                        $key => Setting::get("text.{$locale}.{$key}", ''),
                    ])->all(),
                ])
                ->all(),
            'textKeys' => $textKeys,
            'textFields' => self::TEXT_FIELDS,
            'locales' => self::LOCALES,
            'accents' => ['moon', 'rose', 'amber', 'violet', 'teal'],
            'currentAccent' => Setting::get('accent', 'moon'),
            'ogImageUrl' => Setting::get('og_image'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = collect(self::KEYS)->map(fn (array $rule) => $rule)->all();

        $rules['accent'] = ['required', 'in:moon,rose,amber,violet,teal'];
        $rules['og_image'] = ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];

        // Every text.{locale}.* field is optional free text.
        foreach (self::LOCALES as $locale) {
            foreach (collect(self::TEXT_FIELDS)->flatMap(fn (array $fields) => array_keys($fields)) as $key) {
                $rules["text.{$locale}.{$key}"] = ['nullable', 'string', 'max:1000'];
            }
        }

        $validated = $request->validate($rules);

        // Social preview image lives on the public disk.
        if ($request->hasFile('og_image')) {
            $old = Setting::get('og_image');

            if ($old) {
                Storage::disk('public')->delete($old);
            }

            Setting::set('og_image', $request->file('og_image')->store('social', 'public'));
        }

        unset($validated['og_image']);

        foreach ($validated as $key => $value) {
            if ($key === 'text') {
                foreach ($value as $locale => $texts) {
                    if (! in_array($locale, self::LOCALES, true)) {
                        continue; // never persist unknown locales
                    }

                    foreach ($texts as $textKey => $textValue) {
                        // Empty input = fall back to the built-in default.
                        Setting::set(
                            "text.{$locale}.{$textKey}",
                            is_string($textValue) && trim($textValue) !== '' ? $textValue : null,
                        );
                    }
                }

                continue;
            }

            Setting::set($key, $value);
        }

        return redirect()->route('manage.settings.edit')->with('success', __('flash.settings_saved'));
    }
}

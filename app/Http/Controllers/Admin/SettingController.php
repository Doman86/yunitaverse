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
     * Each group becomes one card in the form.
     */
    private const TEXT_FIELDS = [
        'Landing & Home' => [
            'landing_tagline' => ['Landing tagline', 'The short line under the name on the landing page.'],
            'enter_label' => ['Tombol ENTER', 'Label tombol masuk di landing page.'],
            'home_intro' => ['Home intro', 'Gunakan :name untuk menyisipkan nama profile.'],
            'archive_label' => ['Label HER ARCHIVE', 'Judul pintu pertama di home.'],
            'archive_sub' => ['Sub HER ARCHIVE', 'Deskripsi kecil di bawahnya.'],
            'mod_label' => ['Label MOD', 'Judul pintu kedua di home.'],
            'mod_sub' => ['Sub MOD', 'Deskripsi kecil di bawahnya.'],
            'soundtrack_label' => ['Label SOUNDTRACK', 'Judul pintu ketiga di home.'],
            'soundtrack_sub' => ['Sub SOUNDTRACK', 'Deskripsi kecil di bawahnya.'],
            'myspace_label' => ['Label MY SPACE', 'Judul pintu keempat di home.'],
            'myspace_sub' => ['Sub MY SPACE', 'Deskripsi kecil di bawahnya.'],
            'footer_links_label' => ['Footer home links', 'Teks tautan cepat di bawah menu home.'],
            'footer_note' => ['Footer note', 'Kalimat kecil di footer setiap halaman.'],
        ],
        'Her Archive' => [
            'archive_intro' => ['Archive intro', 'Kalimat pembuka halaman archive.'],
            'archive_label_profile' => ['Label Profile', ''],
            'archive_sub_profile' => ['Sub Profile', ''],
            'archive_label_moments' => ['Label Moments', ''],
            'archive_sub_moments' => ['Sub Moments', ''],
            'archive_label_journey' => ['Label Journey', ''],
            'archive_sub_journey' => ['Sub Journey', ''],
            'archive_label_achievements' => ['Label Achievements', ''],
            'archive_sub_achievements' => ['Sub Achievements', ''],
            'archive_label_activities' => ['Label Activities', ''],
            'archive_sub_activities' => ['Sub Activities', ''],
            'archive_label_favorites' => ['Label Favorites', ''],
            'archive_sub_favorites' => ['Sub Favorites', ''],
        ],
        'MOD' => [
            'mod_question' => ['MOD question', 'Pertanyaan besar di halaman pilih mood.'],
            'mod_good_intro' => ['Intro mood GOOD', ''],
            'mod_normal_intro' => ['Intro mood NORMAL', ''],
            'mod_sad_intro' => ['Intro mood SAD', ''],
            'mod_things_label' => ['Label Things To Do', 'Tombol di hub mood.'],
            'mod_notes_label' => ['Label Little Notes', ''],
            'mod_photos_label' => ['Label Photos', ''],
            'mod_music_label' => ['Label Music', ''],
            'mod_calm_music_label' => ['Label Calm Music', 'Dipakai di mood SAD.'],
            'mod_comfort_notes_label' => ['Label Comfort Notes', 'Dipakai di mood SAD.'],
            'mod_little_things_label' => ['Label Little Things', 'Dipakai di mood SAD.'],
            'mod_surprise_label' => ['Label Surprise', ''],
            'mod_surprise_again' => ['Tombol surprise lagi', 'Muncul di kartu hasil surprise.'],
            'mod_surprise_empty' => ['Surprise kosong', 'Muncul jika belum ada surprise di database.'],
            'mod_things_page_sub' => ['Sub halaman Things', ''],
            'mod_notes_page_sub' => ['Sub halaman Notes', ''],
            'mod_photos_page_sub' => ['Sub halaman Photos', ''],
            'mod_music_page_sub' => ['Sub halaman Music', ''],
        ],
        'Soundtrack & Empty State' => [
            'soundtrack_page_sub' => ['Sub halaman Soundtrack', ''],
            'soundtrack_playlists_label' => ['Judul section Playlists', ''],
            'soundtrack_tracks_label' => ['Judul section Songs', ''],
            'soundtrack_empty' => ['Soundtrack kosong', 'Muncul jika soundtrack masih kosong.'],
            'empty_title' => ['Empty state judul', 'Judul default saat konten kosong.'],
            'empty_message' => ['Empty state pesan', 'Pesan default saat konten kosong.'],
        ],
        'Login & My Space' => [
            'login_welcome' => ['Judul halaman login', ''],
            'login_back' => ['Tautan kembali di login', ''],
            'myspace_welcome' => ['Sambutan My Space', 'Gunakan :name untuk menyisipkan nama user.'],
            'myspace_intro' => ['Intro My Space', ''],
            'myspace_add_moment' => ['Tombol + Add Moment', ''],
            'myspace_add_note' => ['Tombol + Write Note', ''],
            'myspace_add_activity' => ['Tombol + Add Activity', ''],
            'myspace_add_memory' => ['Tombol + Add Memory', ''],
            'myspace_add_mod' => ['Tombol + Add MOD Content', ''],
        ],
    ];

    public function edit(): View
    {
        $textKeys = collect(self::TEXT_FIELDS)
            ->flatMap(fn (array $fields) => array_keys($fields))
            ->all();

        return view('manage.settings', [
            'settings' => collect(self::KEYS)->mapWithKeys(fn (array $rule, string $key) => [
                $key => Setting::get($key, ''),
            ]),
            'texts' => collect($textKeys)
                ->mapWithKeys(fn (string $key) => [$key => Setting::get("text.{$key}", '')])
                ->all(),
            'textFields' => self::TEXT_FIELDS,
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

        // Every text.* field is optional free text.
        foreach (self::TEXT_FIELDS as $fields) {
            foreach (array_keys($fields) as $key) {
                $rules["text.{$key}"] = ['nullable', 'string', 'max:1000'];
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
                foreach ($value as $textKey => $textValue) {
                    // Empty input = kembali ke default bawaan website.
                    Setting::set("text.{$textKey}", $textValue !== null && trim($textValue) !== '' ? $textValue : null);
                }

                continue;
            }

            Setting::set($key, $value);
        }

        return redirect()->route('manage.settings.edit')->with('success', 'Settings disimpan.');
    }
}

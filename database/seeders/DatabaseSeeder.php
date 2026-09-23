<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Favorite;
use App\Models\Journey;
use App\Models\Memory;
use App\Models\Moment;
use App\Models\ModNote;
use App\Models\ModPhoto;
use App\Models\ModPlaylist;
use App\Models\ModSurprise;
use App\Models\ModThingToDo;
use App\Models\Note;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\Soundtrack;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ----- Default users (change passwords before production!) -----
        $admin = User::query()->updateOrCreate(
            ['username' => 'Doman'],
            [
                'name' => 'Doman',
                'email' => 'doman@yunitaverse.local',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $yunita = User::query()->updateOrCreate(
            ['username' => 'Yunita'],
            [
                'name' => 'Yunita Dwi Alung',
                'email' => 'yunita@yunitaverse.local',
                'role' => 'yunita',
                'password' => Hash::make('password'),
            ]
        );

        // ----- Settings -----
        // firstOrCreate: never overwrite text the admin may already have edited.
        $settings = [
            'site_name' => 'YUNITAVERSE',
            'site_tagline' => 'a little universe of her world',
            'timezone' => 'Asia/Jakarta',
            'profile_name' => 'Yunita Dwi Alung',
            'profile_bio' => 'A little universe of Yunita Dwi Alung.',
            'theme_color' => '#0c0a09',
            'meta_description' => 'A little universe of Yunita Dwi Alung.',
            'og_title' => 'YUNITAVERSE',
            'og_description' => 'A little universe of Yunita Dwi Alung.',
            'accent' => 'moon',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('settings.all');

        // ----- Profile -----
        Profile::query()->updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Yunita Dwi Alung',
                'title' => 'the girl of this universe',
                'bio' => 'Hi, I am Yunita. Welcome to my little universe.',
                'about' => "This is a small space that keeps the pieces of my world — quiet days, little wins, favorite songs, and moments worth remembering.",
                'favorites' => [
                    'Music before sleeping',
                    'Rainy afternoon',
                    'Warm drinks',
                    'Quiet places',
                ],
                'quote' => 'Enjoy the little things.',
            ]
        );

        // ----- Moments -----
        $moments = [
            ['title' => 'Sunday Afternoon', 'caption' => 'One of those quiet afternoons.', 'date' => '2026-09-20', 'category' => 'daily'],
            ['title' => 'First Rain This Month', 'caption' => 'The sky finally spoke.', 'date' => '2026-09-12', 'category' => 'weather'],
            ['title' => 'Warm Drink, Quiet Night', 'caption' => 'Simple things that feel like home.', 'date' => '2026-09-05', 'category' => 'little things'],
        ];

        foreach ($moments as $moment) {
            $yunita->moments()->create($moment + ['status' => 'published']);
        }

        // ----- Journey -----
        $journeys = [
            ['title' => 'First Chapter', 'year' => 2022, 'caption' => 'Where it all began.', 'description' => 'The first little steps into a new world.'],
            ['title' => 'A New Beginning', 'year' => 2023, 'caption' => 'Turning pages.', 'description' => 'New places, new faces, new lessons.'],
            ['title' => 'Another Little Step', 'year' => 2024, 'caption' => 'Still walking, still growing.'],
        ];

        foreach ($journeys as $journey) {
            $yunita->journeys()->create($journey + ['is_active' => true]);
        }

        // ----- Achievements -----
        $achievements = [
            ['title' => 'Finished Something Important', 'caption' => 'You did it, even when it wasn’t easy.', 'date' => '2026-06-15'],
            ['title' => 'Kept Going For 100 Days', 'caption' => 'Small every day, still counts.', 'date' => '2026-03-01'],
        ];

        foreach ($achievements as $achievement) {
            $yunita->achievements()->create($achievement + ['status' => 'published']);
        }

        // ----- Activities -----
        $activities = [
            ['title' => 'Studying', 'caption' => 'A little progress every day.', 'category' => 'school'],
            ['title' => 'Organization', 'caption' => 'Learning to work with others.', 'category' => 'organization'],
            ['title' => 'Little Projects', 'caption' => 'Making things for fun.', 'category' => 'project'],
        ];

        foreach ($activities as $activity) {
            $yunita->activities()->create($activity + ['status' => 'published']);
        }

        // ----- Favorites -----
        $favorites = [
            ['title' => 'Late Night Songs', 'category' => 'music', 'caption' => 'For when everything gets quiet.'],
            ['title' => 'Warm Toast', 'category' => 'food', 'caption' => 'Simple, warm, honest.'],
            ['title' => 'Studio Ghibli Films', 'category' => 'movies', 'caption' => 'Soft worlds to rest in.'],
            ['title' => 'Little Poetry Books', 'category' => 'books', 'caption' => 'A few pages before sleep.'],
            ['title' => 'Libraries', 'category' => 'places', 'caption' => 'Quiet rooms full of stories.'],
            ['title' => 'Journaling', 'category' => 'hobbies', 'caption' => 'Keeping the little things.'],
        ];

        foreach ($favorites as $favorite) {
            $yunita->favorites()->create($favorite + ['status' => 'published']);
        }

        // ----- Archive notes + memories -----
        $yunita->notes()->create(['title' => 'A Small Reminder', 'content' => 'Not every day has to be big. Small days matter too.', 'mood' => 'all', 'status' => 'published']);
        $yunita->memories()->create(['title' => 'That One Evening', 'content' => 'The sky was orange and everything felt calm.', 'date' => '2026-08-30', 'status' => 'published']);

        // ----- MOD: things to do -----
        $things = [
            ['title' => 'Go Outside', 'caption' => 'A little fresh air might be nice today.', 'mood' => 'good', 'order' => 1],
            ['title' => 'Make Your Favorite Drink', 'caption' => 'And enjoy it slowly.', 'mood' => 'normal', 'order' => 1],
            ['title' => 'Stretch For Five Minutes', 'caption' => 'Your body deserves a little care.', 'mood' => 'all', 'order' => 2],
            ['title' => 'Open The Window', 'caption' => 'Let the world in for a while.', 'mood' => 'sad', 'order' => 1],
            ['title' => 'Write Three Good Things', 'caption' => 'Tiny ones count too.', 'mood' => 'good', 'order' => 2],
        ];

        foreach ($things as $thing) {
            $yunita->modThingsToDo()->create($thing + ['status' => 'published']);
        }

        // ----- MOD: notes -----
        $modNotes = [
            ['title' => null, 'content' => 'Hari ini jangan lupa menikmati hal kecil.', 'mood' => 'good'],
            ['title' => null, 'content' => 'Kamu sudah melakukan lebih dari yang kamu kira.', 'mood' => 'good'],
            ['title' => null, 'content' => 'Hari yang biasa juga berharga.', 'mood' => 'normal'],
            ['title' => null, 'content' => 'Pelan-pelan saja. Tidak ada yang buru-buru.', 'mood' => 'normal'],
            ['title' => null, 'content' => 'Istirahat itu bukan berarti berhenti. Kamu boleh rehat.', 'mood' => 'sad'],
            ['title' => null, 'content' => 'Hari yang berat akan berlalu juga. Kamu tidak sendiri.', 'mood' => 'sad'],
        ];

        foreach ($modNotes as $note) {
            $yunita->modNotes()->create($note + ['status' => 'published']);
        }

        // ----- MOD: playlists (legal Spotify embeds) -----
        $modPlaylists = [
            ['title' => 'Sunny Day Vibes', 'caption' => 'For bright days.', 'mood' => 'good', 'spotify_url' => 'https://open.spotify.com/playlist/37i9dQZF1DX0XUexfV5EI6'],
            ['title' => 'Soft & Calm', 'caption' => 'Something to listen to when everything gets quiet.', 'mood' => 'sad', 'spotify_url' => 'https://open.spotify.com/playlist/37i9dQZF1DWXe9gFGH69X7'],
        ];

        foreach ($modPlaylists as $playlist) {
            $yunita->modPlaylists()->create($playlist + ['status' => 'published']);
        }

        // ----- Soundtracks -----
        $yunita->modPlaylists()->create(['title' => 'Late Night Playlist', 'caption' => 'When the world sleeps first.', 'mood' => 'all', 'spotify_url' => 'https://open.spotify.com/playlist/37i9dQZF1DX4WY2GeqaPQY', 'status' => 'published']);

        Soundtrack::create(['title' => 'Songs for the Universe', 'caption' => 'The main soundtrack of this place.', 'kind' => 'playlist', 'spotify_url' => 'https://open.spotify.com/playlist/37i9dQZF1DX0XUexfV5EI6', 'created_by' => $yunita->id, 'status' => 'published']);
        Soundtrack::create(['title' => 'Walking On Sunshine', 'caption' => 'A little energy in a song.', 'kind' => 'track', 'spotify_url' => 'https://open.spotify.com/track/08mG3Y1vlJMYxk4wSjH9Zg', 'created_by' => $admin->id, 'status' => 'published']);

        // ----- MOD: surprises -----
        $surprises = [
            ['type' => 'quote', 'title' => 'Little Quote', 'content' => 'Enjoy the little things, for one day you may look back and realize they were the big things.', 'mood' => 'all'],
            ['type' => 'note', 'title' => 'Little Message', 'content' => 'Hari ini kamu hebat. Serius.', 'mood' => 'good'],
            ['type' => 'quote', 'title' => null, 'content' => 'Blessed are the curious, for they shall have adventures.', 'mood' => 'normal'],
            ['type' => 'note', 'title' => null, 'content' => 'Pelan-pelan saja. Nggak ada yang buru-buru.', 'mood' => 'sad'],
            ['type' => 'music', 'title' => 'A song for you', 'link' => 'https://open.spotify.com/track/08mG3Y1vlJMYxk4wSjH9Zg', 'mood' => 'good'],
        ];

        foreach ($surprises as $surprise) {
            $yunita->modSurprises()->create($surprise + ['status' => 'published']);
        }
    }
}

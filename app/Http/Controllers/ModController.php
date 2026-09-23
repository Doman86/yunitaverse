<?php

namespace App\Http\Controllers;

use App\Models\ModNote;
use App\Models\ModPhoto;
use App\Models\ModPlaylist;
use App\Models\ModThingToDo;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModController extends Controller
{
    public const MOODS = ['good', 'normal', 'sad'];

    public function index(): View
    {
        return view('mod.select');
    }

    public function show(string $mood): View
    {
        abort_unless(in_array($mood, self::MOODS, true), 404);

        return view("mod.{$mood}", ['mood' => $mood]);
    }

    public function things(string $mood): View
    {
        abort_unless(in_array($mood, self::MOODS, true), 404);

        return view('mod.things', [
            'mood' => $mood,
            'things' => ModThingToDo::query()
                ->published()
                ->whereIn('mood', [$mood, 'all'])
                ->orderBy('order')
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function notes(string $mood): View
    {
        abort_unless(in_array($mood, self::MOODS, true), 404);

        return view('mod.notes', [
            'mood' => $mood,
            'notes' => ModNote::query()
                ->published()
                ->whereIn('mood', [$mood, 'all'])
                ->latest('date')
                ->latest('created_at')
                ->get(),
        ]);
    }

    public function photos(string $mood): View
    {
        abort_unless(in_array($mood, self::MOODS, true), 404);

        return view('mod.photos', [
            'mood' => $mood,
            'photos' => ModPhoto::query()
                ->published()
                ->whereIn('mood', [$mood, 'all'])
                ->latest('date')
                ->latest('created_at')
                ->get(),
        ]);
    }

    public function music(string $mood): View
    {
        abort_unless(in_array($mood, self::MOODS, true), 404);

        return view('mod.music', [
            'mood' => $mood,
            'playlists' => ModPlaylist::query()
                ->published()
                ->whereIn('mood', [$mood, 'all'])
                ->latest()
                ->get(),
        ]);
    }

    /**
     * JSON endpoint for the Surprise box.
     */
    public function surprise(Request $request)
    {
        $data = $request->validate(['mood' => ['nullable', 'string', 'max:20']]);

        $surprise = \App\Models\ModSurprise::draw($data['mood'] ?? null);

        if (! $surprise) {
            return response()->json([
                'type' => 'note',
                'title' => null,
                'content' => Setting::get('text.mod_surprise_empty', 'Nothing is hidden here yet. Come back later. ✦'),
                'caption' => null,
                'image' => null,
                'link' => null,
                'embed' => null,
            ]);
        }

        return response()->json([
            'type' => $surprise->type,
            'title' => $surprise->title,
            'caption' => $surprise->caption,
            'content' => $surprise->content,
            'image' => $surprise->image ? \Illuminate\Support\Facades\Storage::url($surprise->image) : null,
            'link' => $surprise->link,
            'embed' => $surprise->type === 'music' && $surprise->link
                ? str_replace('open.spotify.com/', 'open.spotify.com/embed/', $surprise->link)
                : null,
        ]);
    }
}

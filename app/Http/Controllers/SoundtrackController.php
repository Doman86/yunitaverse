<?php

namespace App\Http\Controllers;

use App\Models\Soundtrack;
use Illuminate\View\View;

class SoundtrackController extends Controller
{
    public function index(): View
    {
        return view('soundtrack', [
            'playlists' => Soundtrack::query()->published()->where('kind', 'playlist')->latest()->get(),
            'tracks' => Soundtrack::query()->published()->where('kind', 'track')->latest()->get(),
        ]);
    }
}

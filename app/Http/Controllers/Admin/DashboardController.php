<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
use App\Models\Soundtrack;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('manage.dashboard', [
            'counts' => [
                'Moments' => Moment::count(),
                'Journey' => Journey::count(),
                'Achievements' => Achievement::count(),
                'Activities' => Activity::count(),
                'Favorites' => Favorite::count(),
                'Notes' => Note::count(),
                'Memories' => Memory::count(),
                'MOD Things' => ModThingToDo::count(),
                'MOD Notes' => ModNote::count(),
                'MOD Photos' => ModPhoto::count(),
                'MOD Playlists' => ModPlaylist::count(),
                'MOD Surprises' => ModSurprise::count(),
                'Soundtracks' => Soundtrack::count(),
                'Users' => User::count(),
            ],
        ]);
    }
}

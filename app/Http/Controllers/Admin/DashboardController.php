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
                __('admin.moments') => Moment::count(),
                __('admin.journey') => Journey::count(),
                __('admin.achievements') => Achievement::count(),
                __('admin.activities') => Activity::count(),
                __('admin.favorites') => Favorite::count(),
                __('admin.notes') => Note::count(),
                __('admin.memories') => Memory::count(),
                __('admin.things_to_do') => ModThingToDo::count(),
                __('admin.mod_notes') => ModNote::count(),
                __('admin.photos') => ModPhoto::count(),
                __('admin.mod_playlists') => ModPlaylist::count(),
                __('admin.surprises') => ModSurprise::count(),
                __('admin.soundtrack') => Soundtrack::count(),
                __('admin.users') => User::count(),
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Favorite;
use App\Models\Journey;
use App\Models\Moment;
use App\Models\Profile;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    public function index(): View
    {
        return view('archive.index');
    }

    public function profile(): View
    {
        return view('archive.profile', [
            'profile' => Profile::query()->where('is_active', true)->first(),
        ]);
    }

    public function moments(): View
    {
        return view('archive.moments', [
            'moments' => Moment::query()->published()->latest('date')->latest('created_at')->get(),
        ]);
    }

    public function journey(): View
    {
        return view('archive.journey', [
            'journeys' => Journey::query()
                ->where('is_active', true)
                ->orderByDesc('year')
                ->orderByDesc('date')
                ->get(),
        ]);
    }

    public function achievements(): View
    {
        return view('archive.achievements', [
            'achievements' => Achievement::query()->published()->latest('date')->get(),
        ]);
    }

    public function activities(): View
    {
        return view('archive.activities', [
            'activities' => Activity::query()->published()->latest('date')->get(),
        ]);
    }

    public function favorites(): View
    {
        $favorites = Favorite::query()->published()->get()->groupBy('category');

        return view('archive.favorites', [
            'favorites' => $favorites,
            'categories' => Favorite::CATEGORIES,
        ]);
    }
}

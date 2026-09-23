<?php

namespace App\Providers;

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
use App\Policies\ContentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Ownership policy for every content type (Yunita can only touch her own).
        Gate::policy(Moment::class, ContentPolicy::class);
        Gate::policy(Note::class, ContentPolicy::class);
        Gate::policy(Memory::class, ContentPolicy::class);
        Gate::policy(Journey::class, ContentPolicy::class);
        Gate::policy(Achievement::class, ContentPolicy::class);
        Gate::policy(Activity::class, ContentPolicy::class);
        Gate::policy(Favorite::class, ContentPolicy::class);
        Gate::policy(ModThingToDo::class, ContentPolicy::class);
        Gate::policy(ModNote::class, ContentPolicy::class);
        Gate::policy(ModPhoto::class, ContentPolicy::class);
        Gate::policy(ModPlaylist::class, ContentPolicy::class);
        Gate::policy(ModSurprise::class, ContentPolicy::class);
        Gate::policy(Soundtrack::class, ContentPolicy::class);

        // Settings + accent shared by every visitor-facing view.
        View::composer('*', \App\View\Composers\SiteComposer::class);
    }
}

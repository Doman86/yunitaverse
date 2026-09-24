<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ModController;
use App\Http\Controllers\MySpaceController;
use App\Http\Controllers\SoundtrackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Language switch — keeps the visitor on the exact same page
|--------------------------------------------------------------------------
*/
Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['id', 'en'], true), 404);

    session(['locale' => $locale]);

    return redirect()->back();
})->name('locale.switch');

/*
|--------------------------------------------------------------------------
| Public — visitor, no login required
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('archive')->name('archive.')->group(function () {
    Route::get('/', [ArchiveController::class, 'index'])->name('index');
    Route::get('/profile', [ArchiveController::class, 'profile'])->name('profile');
    Route::get('/moments', [ArchiveController::class, 'moments'])->name('moments');
    Route::get('/journey', [ArchiveController::class, 'journey'])->name('journey');
    Route::get('/achievements', [ArchiveController::class, 'achievements'])->name('achievements');
    Route::get('/activities', [ArchiveController::class, 'activities'])->name('activities');
    Route::get('/favorites', [ArchiveController::class, 'favorites'])->name('favorites');
});

Route::prefix('mod')->name('mod.')->group(function () {
    Route::get('/', [ModController::class, 'index'])->name('index');
    Route::get('/good', [ModController::class, 'show'])->defaults('mood', 'good')->name('good');
    Route::get('/normal', [ModController::class, 'show'])->defaults('mood', 'normal')->name('normal');
    Route::get('/sad', [ModController::class, 'show'])->defaults('mood', 'sad')->name('sad');

    Route::get('/{mood}/things', [ModController::class, 'things'])->name('things');
    Route::get('/{mood}/notes', [ModController::class, 'notes'])->name('notes');
    Route::get('/{mood}/photos', [ModController::class, 'photos'])->name('photos');
    Route::get('/{mood}/music', [ModController::class, 'music'])->name('music');
    Route::get('/surprise', [ModController::class, 'surprise'])->name('surprise');
});

Route::get('/soundtrack', [SoundtrackController::class, 'index'])->name('soundtrack');

/*
|--------------------------------------------------------------------------
| Auth — simple login, no public registration
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| My Space — Yunita's personal corner (ownership enforced)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('my-space')->name('my-space.')->group(function () {
    Route::get('/', [MySpaceController::class, 'index'])->name('index');

    Route::get('/{type}', [MySpaceController::class, 'type'])->name('type');
    Route::get('/{type}/new', [MySpaceController::class, 'create'])->name('create');
    Route::post('/{type}', [MySpaceController::class, 'store'])->name('store');
    Route::get('/{type}/{id}/edit', [MySpaceController::class, 'edit'])->name('edit');
    Route::put('/{type}/{id}', [MySpaceController::class, 'update'])->name('update');
    Route::delete('/{type}/{id}', [MySpaceController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Manage — hidden admin area (/manage/login, no links in public UI)
|--------------------------------------------------------------------------
*/
Route::prefix('manage')->name('manage.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/content/{type}', [ContentController::class, 'index'])->name('content.index');
        Route::get('/content/{type}/new', [ContentController::class, 'create'])->name('content.create');
        Route::post('/content/{type}', [ContentController::class, 'store'])->name('content.store');
        Route::get('/content/{type}/{id}/edit', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('/content/{type}/{id}', [ContentController::class, 'update'])->name('content.update');
        Route::delete('/content/{type}/{id}', [ContentController::class, 'destroy'])->name('content.destroy');
        Route::post('/content/{type}/{id}/toggle', [ContentController::class, 'toggle'])->name('content.toggle');
    });
});

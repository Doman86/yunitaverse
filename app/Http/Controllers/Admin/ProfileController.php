<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('manage.profile', [
            'profile' => Profile::query()->firstOrCreate(['id' => 1]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'about' => ['nullable', 'string', 'max:5000'],
            'favorites' => ['nullable', 'string', 'max:2000'],
            'quote' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $profile = Profile::query()->firstOrCreate(['id' => 1]);

        if ($request->hasFile('photo')) {
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $data['photo'] = $request->file('photo')->store('profile', 'public');
        }

        // Favorites stored as simple lines -> JSON list.
        if (array_key_exists('favorites', $data)) {
            $data['favorites'] = collect(preg_split('/\r\n|\r|\n/', (string) $data['favorites']))
                ->map(fn (string $line) => trim($line))
                ->filter()
                ->values()
                ->all();
        }

        $profile->update($data);

        return redirect()->route('manage.profile.edit')->with('success', __('flash.profile_saved'));
    }
}

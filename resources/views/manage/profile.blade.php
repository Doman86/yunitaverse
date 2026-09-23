@extends('layouts.manage')

@section('title', 'Profile — Manage')

@section('content')
<h1 class="font-serif text-3xl">Profile</h1>
<p class="mt-2 text-sm text-night-400">Public profile shown in Her Archive.</p>

<form method="POST" action="{{ route('manage.profile.update') }}" enctype="multipart/form-data" class="card mt-8 max-w-xl space-y-5 p-7">
    @csrf
    @method('PUT')

    <div>
        <label class="text-xs uppercase tracking-widest text-night-500">Name *</label>
        <input type="text" name="name" value="{{ old('name', $profile->name) }}" required
               class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
    </div>

    <div>
        <label class="text-xs uppercase tracking-widest text-night-500">Title</label>
        <input type="text" name="title" value="{{ old('title', $profile->title) }}"
               class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
    </div>

    <div>
        <label class="text-xs uppercase tracking-widest text-night-500">Short bio</label>
        <textarea name="bio" rows="3" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('bio', $profile->bio) }}</textarea>
    </div>

    <div>
        <label class="text-xs uppercase tracking-widest text-night-500">About her</label>
        <textarea name="about" rows="6" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('about', $profile->about) }}</textarea>
    </div>

    <div>
        <label class="text-xs uppercase tracking-widest text-night-500">Favorites (one per line)</label>
        <textarea name="favorites" rows="5" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('favorites', implode("\n", $profile->favorites ?? [])) }}</textarea>
    </div>

    <div>
        <label class="text-xs uppercase tracking-widest text-night-500">Quote / caption</label>
        <input type="text" name="quote" value="{{ old('quote', $profile->quote) }}"
               class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
    </div>

    <div>
        <label class="text-xs uppercase tracking-widest text-night-500">Photo (jpg/png/webp, max 5MB)</label>
        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
               class="mt-2 w-full text-sm text-night-400 file:mr-4 file:rounded-full file:border-0 file:bg-night-800 file:px-4 file:py-2 file:text-sm file:text-night-100">
        @if($profile->photo)
            <img src="{{ $profile->photoUrl() }}" alt="" class="mt-3 h-24 w-24 rounded-full border border-night-700 object-cover">
        @endif
    </div>

    <button type="submit" class="w-full rounded-xl bg-night-100 py-3 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
        Save profile
    </button>
</form>
@endsection

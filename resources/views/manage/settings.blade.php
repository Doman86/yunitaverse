@extends('layouts.manage')

@section('title', 'Settings — Manage')

@section('content')
<h1 class="font-serif text-3xl">Settings</h1>
<p class="mt-2 text-sm text-night-400">Semua teks di website publik diatur dari sini. Kosongkan untuk kembali ke default.</p>

<form method="POST" action="{{ route('manage.settings.update') }}" enctype="multipart/form-data" class="mt-8 max-w-2xl space-y-8">
    @csrf
    @method('PUT')

    {{-- Website --}}
    <section class="card space-y-5 p-7">
        <h2 class="text-xs uppercase tracking-[0.25em] text-night-500">Website</h2>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">Website name *</label>
            <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">Website tagline</label>
            <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}"
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">Profile name *</label>
            <input type="text" name="profile_name" value="{{ old('profile_name', $settings['profile_name']) }}" required
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">Profile bio</label>
            <textarea name="profile_bio" rows="3" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('profile_bio', $settings['profile_bio']) }}</textarea>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="text-xs uppercase tracking-widest text-night-500">Timezone *</label>
                <input type="text" name="timezone" value="{{ old('timezone', $settings['timezone']) }}" required
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-night-500">Theme accent</label>
                <select name="accent" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
                    @foreach($accents as $accentKey)
                        <option value="{{ $accentKey }}" {{ $currentAccent === $accentKey ? 'selected' : '' }}>{{ ucfirst($accentKey) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">Theme color (meta, hex)</label>
            <input type="text" name="theme_color" value="{{ old('theme_color', $settings['theme_color']) }}" placeholder="#0c0a09"
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>
    </section>

    {{-- Social preview --}}
    <section class="card space-y-5 p-7">
        <h2 class="text-xs uppercase tracking-[0.25em] text-night-500">Social preview</h2>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">Meta description</label>
            <textarea name="meta_description" rows="2" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('meta_description', $settings['meta_description']) }}</textarea>
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">OG title</label>
            <input type="text" name="og_title" value="{{ old('og_title', $settings['og_title']) }}"
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">OG description</label>
            <textarea name="og_description" rows="2" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('og_description', $settings['og_description']) }}</textarea>
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">Social preview image (jpg/png/webp, max 5MB)</label>
            <input type="file" name="og_image" accept="image/jpeg,image/png,image/webp"
                   class="mt-2 w-full text-sm text-night-400 file:mr-4 file:rounded-full file:border-0 file:bg-night-800 file:px-4 file:py-2 file:text-sm file:text-night-100">
            @if($ogImageUrl)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($ogImageUrl) }}" alt="" class="mt-3 h-20 rounded-lg border border-night-700 object-cover">
            @endif
        </div>
    </section>

    {{-- Text sections --}}
    @foreach($textFields as $sectionLabel => $fields)
        <section class="card space-y-5 p-7">
            <h2 class="text-xs uppercase tracking-[0.25em] text-night-500">{{ $sectionLabel }}</h2>

            @foreach($fields as $key => [$label, $hint])
                <div>
                    <label class="text-xs uppercase tracking-widest text-night-500">{{ $label }}</label>
                    @if($hint)
                        <p class="mt-0.5 text-[11px] text-night-600">{{ $hint }}</p>
                    @endif
                    <input type="text" name="text[{{ $key }}]" value="{{ old("text.{$key}", $texts[$key]) }}"
                           class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
                </div>
            @endforeach
        </section>
    @endforeach

    <button type="submit" class="w-full rounded-xl bg-night-100 py-3 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
        Save settings
    </button>
</form>
@endsection

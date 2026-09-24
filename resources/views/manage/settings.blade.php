@extends('layouts.manage')

@section('title', __('admin.settings') . ' — ' . __('common.admin_area'))

@section('content')
<h1 class="font-serif text-3xl">{{ __('admin.settings') }}</h1>
<p class="mt-2 text-sm text-night-400">{{ __('admin.settings_sub') }}</p>

<form method="POST" action="{{ route('manage.settings.update') }}" enctype="multipart/form-data" class="mt-8 max-w-2xl space-y-8">
    @csrf
    @method('PUT')

    {{-- Website --}}
    <section class="card space-y-5 p-7">
        <h2 class="text-xs uppercase tracking-[0.25em] text-night-500">{{ __('admin.website') }}</h2>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.website_name') }} {{ __('admin.required_mark') }}</label>
            <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.website_tagline') }}</label>
            <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}"
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.profile_name') }} {{ __('admin.required_mark') }}</label>
            <input type="text" name="profile_name" value="{{ old('profile_name', $settings['profile_name']) }}" required
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.profile_bio') }}</label>
            <textarea name="profile_bio" rows="3" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('profile_bio', $settings['profile_bio']) }}</textarea>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.timezone') }} {{ __('admin.required_mark') }}</label>
                <input type="text" name="timezone" value="{{ old('timezone', $settings['timezone']) }}" required
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.theme_accent') }}</label>
                <select name="accent" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
                    @foreach($accents as $accentKey)
                        <option value="{{ $accentKey }}" {{ $currentAccent === $accentKey ? 'selected' : '' }}>{{ ucfirst($accentKey) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.theme_color') }}</label>
            <input type="text" name="theme_color" value="{{ old('theme_color', $settings['theme_color']) }}" placeholder="#0c0a09"
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>
    </section>

    {{-- Social preview --}}
    <section class="card space-y-5 p-7">
        <h2 class="text-xs uppercase tracking-[0.25em] text-night-500">{{ __('admin.social_preview') }}</h2>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.meta_description') }}</label>
            <textarea name="meta_description" rows="2" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('meta_description', $settings['meta_description']) }}</textarea>
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.og_title') }}</label>
            <input type="text" name="og_title" value="{{ old('og_title', $settings['og_title']) }}"
                   class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.og_description') }}</label>
            <textarea name="og_description" rows="2" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('og_description', $settings['og_description']) }}</textarea>
        </div>

        <div>
            <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.og_image') }}</label>
            <input type="file" name="og_image" accept="image/jpeg,image/png,image/webp"
                   class="mt-2 w-full text-sm text-night-400 file:mr-4 file:rounded-full file:border-0 file:bg-night-800 file:px-4 file:py-2 file:text-sm file:text-night-100">
            @if($ogImageUrl)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($ogImageUrl) }}" alt="" class="mt-3 h-20 rounded-lg border border-night-700 object-cover">
            @endif
        </div>
    </section>

    {{-- Text sections — one block per language --}}
    @foreach($locales as $locale)
        @php($langNames = ['id' => 'Bahasa Indonesia', 'en' => 'English'])
        <section class="card space-y-5 p-7">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xs uppercase tracking-[0.25em] text-night-500">{{ __('admin.text_section', ['section' => $langNames[$locale] ?? $locale]) }}</h2>
                <span class="rounded-full border border-night-600 px-2.5 py-0.5 text-[10px] uppercase tracking-widest text-night-400">{{ strtoupper($locale) }}</span>
            </div>
            <p class="-mt-3 text-[11px] text-night-600">{{ __('admin.settings_sub') }}</p>

            @foreach($textFields as $sectionKey => $fields)
                <h3 class="pt-2 text-[10px] uppercase tracking-[0.2em] text-night-600">{{ __($sectionKey) }}</h3>

                @foreach($fields as $key => [$labelKey, $hintKey])
                    <div>
                        <label class="text-xs uppercase tracking-widest text-night-500">{{ __($labelKey) }}</label>
                        @if($hintKey)
                            <p class="mt-0.5 text-[11px] text-night-600">{{ __($hintKey) }}</p>
                        @endif
                        <input type="text" name="text[{{ $locale }}][{{ $key }}]" value="{{ old("text.{$locale}.{$key}", $texts[$locale][$key]) }}"
                               class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
                    </div>
                @endforeach
            @endforeach
        </section>
    @endforeach

    <button type="submit" class="w-full rounded-xl bg-night-100 py-3 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
        {{ __('admin.save_settings') }}
    </button>
</form>
@endsection

@extends('layouts.visitor')

@section('title', __('common.home') . ' — ' . $siteName)

@section('content')
<section class="starfield relative flex min-h-dvh flex-col items-center justify-center px-6 py-16 text-center">
    <p class="text-2xl text-night-400" aria-hidden="true">☾</p>
    <h1 class="mt-4 font-serif text-4xl sm:text-5xl">
        <span class="text-shimmer">{{ $siteName }}</span>
    </h1>
    <p class="mt-3 text-sm text-night-400">{{ str_replace(':name', $profileName, $text['home_intro']) }}</p>

    <div class="mt-14 grid w-full max-w-3xl grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('archive.index') }}"
           class="card group flex flex-col items-start gap-2 p-7 text-left">
            <span class="text-xl text-night-200">✦</span>
            <span class="font-serif text-2xl text-night-100">{{ $text['archive_label'] }}</span>
            <span class="text-sm text-night-400">{{ $text['archive_sub'] }}</span>
        </a>

        <a href="{{ route('mod.index') }}"
           class="card group flex flex-col items-start gap-2 p-7 text-left">
            <span class="text-xl text-night-200">◐</span>
            <span class="font-serif text-2xl text-night-100">{{ $text['mod_label'] }}</span>
            <span class="text-sm text-night-400">{{ $text['mod_sub'] }}</span>
        </a>

        <a href="{{ route('soundtrack') }}"
           class="card group flex flex-col items-start gap-2 p-7 text-left">
            <span class="text-xl text-night-200">♪</span>
            <span class="font-serif text-2xl text-night-100">{{ $text['soundtrack_label'] }}</span>
            <span class="text-sm text-night-400">{{ $text['soundtrack_sub'] }}</span>
        </a>

        <a href="{{ auth()->check() ? route('my-space.index') : route('login') }}"
           class="card group flex flex-col items-start gap-2 p-7 text-left">
            <span class="text-xl text-night-200">✎</span>
            <span class="font-serif text-2xl text-night-100">{{ $text['myspace_label'] }}</span>
            <span class="text-sm text-night-400">{{ $text['myspace_sub'] }}</span>
        </a>
    </div>

    <div class="mt-12 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs uppercase tracking-widest text-night-600">
        @foreach(preg_split('/\s*·\s*/', (string) $text['footer_links_label']) as $i => $label)
            @if($i > 0)<span>·</span>@endif
            @php($link = match ($i) {
                0 => route('archive.moments'),
                1 => route('archive.journey'),
                default => route('archive.favorites'),
            })
            <a href="{{ $link }}" class="transition-colors hover:text-night-300">{{ trim($label) }}</a>
        @endforeach
    </div>
</section>
@endsection

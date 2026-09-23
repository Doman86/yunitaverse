@extends('layouts.visitor')

@section('title', $siteName . ' — ' . $siteTagline)

@section('content')
<section class="starfield relative flex min-h-dvh flex-col items-center justify-center px-6 text-center">
    <div class="pointer-events-none absolute left-1/2 top-24 -z-10 h-72 w-72 -translate-x-1/2" aria-hidden="true">
        <div class="animate-glow absolute inset-0 rounded-full bg-stone-400/10 blur-3xl"></div>
    </div>

    {{-- Hidden admin entry: 3 taps on the moon logo --}}
    <div x-data="secretMoon(@js(route('manage.login')))" class="select-none">
        <p class="animate-float cursor-pointer text-4xl text-night-200" aria-hidden="true" @click="hit()">☾</p>

        <p class="mt-4 text-sm tracking-[0.3em] uppercase text-night-400" x-show="hint" x-text="hint" x-cloak></p>
    </div>

    <h1 class="mt-6 cursor-default font-serif text-5xl leading-tight sm:text-6xl md:text-7xl">
        <span class="text-shimmer">{{ $siteName }}</span>
    </h1>

    <p class="mt-5 font-serif text-xl italic text-night-400">{{ $profileName }}</p>

    <div class="mt-8 space-y-1" x-data="clock(@js($timezone))">
        <p class="font-serif text-3xl tabular-nums tracking-wide text-night-100" x-text="time">21:47:32</p>
        <p class="text-xs uppercase tracking-[0.25em] text-night-600" x-text="date">Monday, 22 September 2026</p>
    </div>

    <p class="mt-8 max-w-md text-sm text-night-400">{{ $text['landing_tagline'] }}</p>

    <div class="mt-10">
        <a href="{{ route('home') }}"
           class="group inline-flex items-center gap-2 rounded-full border border-night-600 bg-night-800/60 px-10 py-4 text-sm font-semibold tracking-[0.2em] uppercase text-night-100 transition-all duration-300 hover:scale-[1.04] hover:border-night-400 hover:bg-night-700/70 {{ $accent['glow'] }} shadow-lg">
            {{ $text['enter_label'] }}
            <span class="inline-block transition-transform duration-300 group-hover:translate-x-1">✦</span>
        </a>
    </div>
</section>
@endsection

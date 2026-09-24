@extends('layouts.app')

@section('title', $text['myspace_label'])

@section('content')
<header class="mb-10">
    <p class="text-xs uppercase tracking-[0.3em] text-night-500">{{ $text['myspace_label'] }}</p>
    <h1 class="mt-2 font-serif text-4xl">{{ str_replace(':name', auth()->user()->name ?? auth()->user()->username, $text['myspace_welcome']) }}</h1>
    <p class="mt-2 text-sm text-night-400">{{ $text['myspace_intro'] }}</p>
</header>

<div class="mb-10 flex flex-wrap gap-3">
    <a href="{{ route('my-space.create', 'moments') }}" class="rounded-full border border-night-600 bg-night-800/60 px-5 py-2.5 text-sm transition-colors hover:border-night-400">{{ $text['myspace_add_moment'] }}</a>
    <a href="{{ route('my-space.create', 'notes') }}" class="rounded-full border border-night-600 bg-night-800/60 px-5 py-2.5 text-sm transition-colors hover:border-night-400">{{ $text['myspace_add_note'] }}</a>
    <a href="{{ route('my-space.create', 'activities') }}" class="rounded-full border border-night-600 bg-night-800/60 px-5 py-2.5 text-sm transition-colors hover:border-night-400">{{ $text['myspace_add_activity'] }}</a>
    <a href="{{ route('my-space.create', 'memories') }}" class="rounded-full border border-night-600 bg-night-800/60 px-5 py-2.5 text-sm transition-colors hover:border-night-400">{{ $text['myspace_add_memory'] }}</a>
    <a href="{{ route('my-space.create', 'mod-notes') }}" class="rounded-full border border-night-600 bg-night-800/60 px-5 py-2.5 text-sm transition-colors hover:border-night-400">{{ $text['myspace_add_mod'] }}</a>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @foreach([
        'moments',
        'notes',
        'activities',
        'memories',
        'achievements',
        'mod-things',
        'mod-notes',
        'mod-photos',
        'mod-playlists',
        'mod-surprises',
    ] as $type)
        <a href="{{ route('my-space.type', $type) }}" class="card flex items-center justify-between p-5">
            <span class="font-serif text-lg text-night-100">{{ __("my-space.type_{$type}") }}</span>
            <span class="text-sm text-night-500">{{ $counts->get($type) ?? 0 }}</span>
        </a>
    @endforeach
</div>
@endsection

@extends('layouts.visitor')

@section('title', 'Profile — ' . $siteName)

@section('content')
<div class="mx-auto max-w-3xl px-6 py-16">
    <header class="mb-12">
        <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>
        <h1 class="mt-4 font-serif text-4xl sm:text-5xl">Profile</h1>
    </header>

    @if($profile)
        <div class="card p-8 sm:p-10">
            <div class="flex flex-col items-center gap-6 text-center sm:flex-row sm:items-start sm:text-left">
                @if($profile->photo)
                    <img src="{{ $profile->photoUrl() }}" alt="{{ $profile->name }}"
                         class="h-36 w-36 flex-shrink-0 rounded-full border border-night-600 object-cover">
                @else
                    <div class="flex h-36 w-36 flex-shrink-0 items-center justify-center rounded-full border border-night-700 bg-night-800 font-serif text-5xl text-night-400">
                        {{ substr($profile->name, 0, 1) }}
                    </div>
                @endif

                <div>
                    <h2 class="font-serif text-3xl text-night-100">{{ $profile->name }}</h2>
                    @if($profile->title)
                        <p class="mt-1 text-xs uppercase tracking-[0.25em] text-night-400">{{ $profile->title }}</p>
                    @endif
                    @if($profile->bio)
                        <p class="mt-4 text-sm leading-relaxed text-night-400">{{ $profile->bio }}</p>
                    @endif
                </div>
            </div>

            @if($profile->about)
                <div class="mt-10 border-t border-night-700/60 pt-8">
                    <h3 class="text-xs uppercase tracking-[0.25em] text-night-500">About her</h3>
                    <p class="mt-4 whitespace-pre-line text-sm leading-relaxed text-night-300">{{ $profile->about }}</p>
                </div>
            @endif

            @if($profile->favorites)
                <div class="mt-10 border-t border-night-700/60 pt-8">
                    <h3 class="text-xs uppercase tracking-[0.25em] text-night-500">Favorites</h3>
                    <ul class="mt-4 grid grid-cols-1 gap-x-8 gap-y-2 text-sm text-night-300 sm:grid-cols-2">
                        @foreach($profile->favorites as $favorite)
                            <li class="flex items-baseline gap-2">
                                <span class="text-night-600">✦</span> {{ $favorite }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($profile->quote)
                <div class="mt-10 border-t border-night-700/60 pt-8 text-center">
                    <p class="font-serif text-2xl italic text-night-200">“{{ $profile->quote }}”</p>
                </div>
            @endif
        </div>
    @else
        @include('archive.partials.empty', ['title' => 'The profile is resting.', 'message' => 'Come back later.'])
    @endif
</div>
@endsection

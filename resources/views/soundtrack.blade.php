@extends('layouts.visitor')

@section('title', $text['soundtrack_label'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-2xl px-6 py-16">
    <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>
    <h1 class="mt-4 font-serif text-4xl sm:text-5xl">{{ $text['soundtrack_label'] }}</h1>
    <p class="mt-3 text-sm text-night-400">{{ $text['soundtrack_page_sub'] }}</p>

    @if($playlists->isNotEmpty())
        <section class="mt-12">
            <h2 class="mb-6 text-xs uppercase tracking-[0.3em] text-night-500">{{ $text['soundtrack_playlists_label'] }}</h2>
            <div class="space-y-6">
                @foreach($playlists as $playlist)
                    <article class="card p-5">
                        <h3 class="font-serif text-xl text-night-100">{{ $playlist->title }}</h3>
                        @if($playlist->caption)
                            <p class="mt-1 text-sm italic text-night-400">{{ $playlist->caption }}</p>
                        @endif
                        @if($playlist->description)
                            <p class="mt-2 text-sm text-night-300">{{ $playlist->description }}</p>
                        @endif
                        <iframe src="{{ $playlist->embedUrl() }}" class="mt-4 h-[352px] w-full rounded-lg" frameborder="0"
                                allow="encrypted-media" allowfullscreen title="{{ $playlist->title }}"></iframe>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($tracks->isNotEmpty())
        <section class="mt-12">
            <h2 class="mb-6 text-xs uppercase tracking-[0.3em] text-night-500">{{ $text['soundtrack_tracks_label'] }}</h2>
            <div class="space-y-6">
                @foreach($tracks as $track)
                    <article class="card p-5">
                        <h3 class="font-serif text-xl text-night-100">{{ $track->title }}</h3>
                        @if($track->caption)
                            <p class="mt-1 text-sm italic text-night-400">{{ $track->caption }}</p>
                        @endif
                        <iframe src="{{ $track->embedUrl() }}" class="mt-4 h-[152px] w-full rounded-lg" frameborder="0"
                                allow="encrypted-media" allowfullscreen title="{{ $track->title }}"></iframe>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($playlists->isEmpty() && $tracks->isEmpty())
        <div class="mt-10">
            @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['soundtrack_empty']])
        </div>
    @endif
</div>
@endsection

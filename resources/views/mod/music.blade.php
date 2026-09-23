@extends('layouts.visitor')

@section('title', $text['mod_music_label'] . ' — ' . $text['mod_label'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-2xl px-6 py-16">
    <a href="{{ route('mod.' . $mood) }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← MOD</a>
    <h1 class="mt-4 font-serif text-4xl">{{ $text['mod_music_label'] }}</h1>
    <p class="mt-2 text-sm text-night-400">{{ $text['mod_music_page_sub'] }}</p>

    <div class="mt-10 space-y-6">
        @if($playlists->isEmpty())
            @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
        @else
            @foreach($playlists as $playlist)
                <article class="card p-5">
                    <h2 class="font-serif text-xl text-night-100">{{ $playlist->title }}</h2>
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
        @endif
    </div>
</div>
@endsection

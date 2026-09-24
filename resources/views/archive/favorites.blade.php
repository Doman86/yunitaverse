@extends('layouts.visitor')

@section('title', $text['archive_label_favorites'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-4xl px-6 py-16">
    <header class="mb-14">
        <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>
        <h1 class="mt-4 font-serif text-4xl sm:text-5xl">{{ $text['archive_label_favorites'] }}</h1>
        <p class="mt-3 text-sm text-night-400">{{ $text['archive_sub_favorites'] }}</p>
    </header>

    @if($favorites->isEmpty())
        @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
    @else
        @foreach($categories as $key => $label)
            @continue(! $favorites->has($key))
            <section class="mb-12">
                <h2 class="mb-5 font-serif text-2xl text-night-200">{{ __("admin.{$label}") }}</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach($favorites->get($key) as $favorite)
                        <article class="card p-5">
                            <h3 class="font-serif text-lg text-night-100">{{ $favorite->title }}</h3>
                            @if($favorite->caption)
                                <p class="mt-1 text-sm italic text-night-400">{{ $favorite->caption }}</p>
                            @endif
                            @if($favorite->description)
                                <p class="mt-2 text-sm text-night-300">{{ $favorite->description }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    @endif
</div>
@endsection

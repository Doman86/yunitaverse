@extends('layouts.visitor')

@section('title', $text['archive_label_achievements'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-3xl px-6 py-16">
    <header class="mb-14">
        <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>
        <h1 class="mt-4 font-serif text-4xl sm:text-5xl">{{ $text['archive_label_achievements'] }}</h1>
        <p class="mt-3 text-sm text-night-400">{{ $text['archive_sub_achievements'] }}</p>
    </header>

    @if($achievements->isEmpty())
        @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
    @else
        <div class="space-y-4">
            @foreach($achievements as $achievement)
                <article class="card flex flex-col gap-4 p-6 sm:flex-row sm:items-center">
                    @if($achievement->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($achievement->image) }}" alt="{{ $achievement->title }}"
                             class="h-20 w-20 flex-shrink-0 rounded-xl border border-night-700 object-cover">
                    @endif
                    <div class="min-w-0">
                        <h2 class="font-serif text-xl text-night-100">{{ $achievement->title }}</h2>
                        @if($achievement->caption)
                            <p class="mt-1 text-sm italic text-night-400">{{ $achievement->caption }}</p>
                        @endif
                        @if($achievement->description)
                            <p class="mt-2 whitespace-pre-line text-sm text-night-300">{{ $achievement->description }}</p>
                        @endif
                        <p class="mt-3 text-[11px] uppercase tracking-widest text-night-600">{{ optional($achievement->date)->translatedFormat('j F Y') }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection

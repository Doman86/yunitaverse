@extends('layouts.visitor')

@section('title', $text['archive_label_moments'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-5xl px-6 py-16">
    <header class="mb-14">
        <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>
        <h1 class="mt-4 font-serif text-4xl sm:text-5xl">{{ $text['archive_label_moments'] }}</h1>
        <p class="mt-3 text-sm text-night-400">{{ $text['archive_sub_moments'] }}</p>
    </header>

    @if($moments->isEmpty())
        @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
    @else
        <div class="columns-1 gap-6 sm:columns-2 lg:columns-3">
            @foreach($moments as $moment)
                <article class="tape card relative mb-6 break-inside-avoid p-5 pb-6"
                         style="transform: rotate({{ ($loop->index % 3) - 1 }}deg);">
                    @if($moment->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($moment->image) }}" alt="{{ $moment->title }}"
                             class="mb-4 w-full rounded-lg border border-night-700 object-cover">
                    @endif
                    <h2 class="font-serif text-2xl text-night-100">{{ $moment->title }}</h2>
                    @if($moment->caption)
                        <p class="mt-1 text-sm italic text-night-400">{{ $moment->caption }}</p>
                    @endif
                    @if($moment->description)
                        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-night-300">{{ $moment->description }}</p>
                    @endif
                    <p class="mt-4 text-[11px] uppercase tracking-widest text-night-600">
                        {{ optional($moment->date)->format('j F Y') }}
                        @if($moment->category)
                            <span class="mx-1">·</span> {{ $moment->category }}
                        @endif
                    </p>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection

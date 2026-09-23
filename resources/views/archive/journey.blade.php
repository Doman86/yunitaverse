@extends('layouts.visitor')

@section('title', $text['archive_label_journey'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-3xl px-6 py-16">
    <header class="mb-14">
        <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>
        <h1 class="mt-4 font-serif text-4xl sm:text-5xl">{{ $text['archive_label_journey'] }}</h1>
        <p class="mt-3 text-sm text-night-400">{{ $text['archive_sub_journey'] }}</p>
    </header>

    @if($journeys->isEmpty())
        @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
    @else
        <div class="relative border-l border-night-700 pl-8">
            @foreach($journeys as $journey)
                <div class="relative mb-12">
                    <span class="absolute -left-[37px] top-1.5 h-2.5 w-2.5 rounded-full border border-night-400 bg-night-950"></span>

                    <p class="font-serif text-4xl text-night-600">{{ $journey->year ?? optional($journey->date)->format('Y') }}</p>

                    <h2 class="mt-2 font-serif text-2xl text-night-100">
                        {{ $journey->title }}
                        @if($journey->is_featured)
                            <span class="align-middle text-xs text-amber-200/70">✦</span>
                        @endif
                    </h2>
                    @if($journey->caption)
                        <p class="mt-1 text-sm italic text-night-400">{{ $journey->caption }}</p>
                    @endif
                    @if($journey->description)
                        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-night-300">{{ $journey->description }}</p>
                    @endif

                    @if($journey->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($journey->image) }}" alt="{{ $journey->title }}"
                             class="mt-4 max-w-sm rounded-lg border border-night-700 object-cover">
                    @endif

                    @if($journey->category)
                        <p class="mt-3 text-[11px] uppercase tracking-widest text-night-600">{{ $journey->category }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

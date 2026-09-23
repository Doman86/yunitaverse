@extends('layouts.visitor')

@section('title', $text['archive_label_activities'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-4xl px-6 py-16">
    <header class="mb-14">
        <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>
        <h1 class="mt-4 font-serif text-4xl sm:text-5xl">{{ $text['archive_label_activities'] }}</h1>
        <p class="mt-3 text-sm text-night-400">{{ $text['archive_sub_activities'] }}</p>
    </header>

    @if($activities->isEmpty())
        @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            @foreach($activities as $activity)
                <article class="card overflow-hidden">
                    @if($activity->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($activity->image) }}" alt="{{ $activity->title }}"
                             class="h-44 w-full border-b border-night-700 object-cover">
                    @endif
                    <div class="p-6">
                        @if($activity->category)
                            <p class="text-[10px] uppercase tracking-widest text-night-500">{{ $activity->category }}</p>
                        @endif
                        <h2 class="mt-1 font-serif text-xl text-night-100">{{ $activity->title }}</h2>
                        @if($activity->caption)
                            <p class="mt-1 text-sm italic text-night-400">{{ $activity->caption }}</p>
                        @endif
                        @if($activity->description)
                            <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-night-300">{{ $activity->description }}</p>
                        @endif
                        <p class="mt-4 text-[11px] uppercase tracking-widest text-night-600">{{ optional($activity->date)->format('j F Y') }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection

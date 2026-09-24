@extends('layouts.visitor')

@section('title', $text['mod_things_label'] . ' — ' . $text['mod_label'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-2xl px-6 py-16">
    <a href="{{ route('mod.' . $mood) }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ __('admin.mod') }}</a>
    <h1 class="mt-4 font-serif text-4xl">{{ $text['mod_things_label'] }}</h1>
    <p class="mt-2 text-sm text-night-400">{{ $text['mod_things_page_sub'] }}</p>

    <div class="mt-10 space-y-4">
        @if($things->isEmpty())
            @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
        @else
            @foreach($things as $thing)
                <article class="card p-6">
                    @if($thing->link)
                        <a href="{{ $thing->link }}" target="_blank" rel="noopener" class="font-serif text-xl text-night-100 underline decoration-night-600 underline-offset-4">{{ $thing->title }}</a>
                    @else
                        <h2 class="font-serif text-xl text-night-100">{{ $thing->title }}</h2>
                    @endif
                    @if($thing->caption)
                        <p class="mt-1 text-sm italic text-night-400">{{ $thing->caption }}</p>
                    @endif
                    @if($thing->description)
                        <p class="mt-2 whitespace-pre-line text-sm text-night-300">{{ $thing->description }}</p>
                    @endif
                    @if($thing->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($thing->image) }}" alt="{{ $thing->title }}" class="mt-4 rounded-lg border border-night-700">
                    @endif
                </article>
            @endforeach
        @endif
    </div>
</div>
@endsection

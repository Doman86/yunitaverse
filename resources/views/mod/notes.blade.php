@extends('layouts.visitor')

@section('title', $text['mod_notes_label'] . ' — ' . $text['mod_label'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-2xl px-6 py-16">
    <a href="{{ route('mod.' . $mood) }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ __('admin.mod') }}</a>
    <h1 class="mt-4 font-serif text-4xl">{{ $text['mod_notes_label'] }}</h1>
    <p class="mt-2 text-sm text-night-400">{{ $text['mod_notes_page_sub'] }}</p>

    <div class="mt-10 space-y-4">
        @if($notes->isEmpty())
            @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
        @else
            @foreach($notes as $note)
                <article class="card p-6">
                    @if($note->title)
                        <h2 class="font-serif text-xl text-night-100">{{ $note->title }}</h2>
                    @endif
                    @if($note->caption)
                        <p class="mt-1 text-sm italic text-night-400">{{ $note->caption }}</p>
                    @endif
                    <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-night-300">{{ $note->content }}</p>
                    @if($note->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($note->image) }}" alt="" class="mt-4 rounded-lg border border-night-700">
                    @endif
                    <p class="mt-4 text-[11px] uppercase tracking-widest text-night-600">{{ optional($note->date)->translatedFormat('j F Y') }}</p>
                </article>
            @endforeach
        @endif
    </div>
</div>
@endsection

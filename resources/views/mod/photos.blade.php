@extends('layouts.visitor')

@section('title', $text['mod_photos_label'] . ' — ' . $text['mod_label'] . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-4xl px-6 py-16">
    <a href="{{ route('mod.' . $mood) }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ __('admin.mod') }}</a>
    <h1 class="mt-4 font-serif text-4xl">{{ $text['mod_photos_label'] }}</h1>
    <p class="mt-2 text-sm text-night-400">{{ $text['mod_photos_page_sub'] }}</p>

    @if($photos->isEmpty())
        <div class="mt-10">
            @include('archive.partials.empty', ['title' => $text['empty_title'], 'message' => $text['empty_message']])
        </div>
    @else
        {{-- One lightbox component wraps the whole grid; previously the overlay
             was duplicated inside the loop for every photo. --}}
        <div class="mt-10 columns-1 gap-4 sm:columns-2" x-data="lightbox()">
            @foreach($photos as $photo)
                <figure class="card mb-4 break-inside-avoid overflow-hidden p-2">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($photo->image) }}"
                         alt="{{ $photo->title ?? __('common.photo') }}"
                         class="w-full cursor-zoom-in rounded-lg object-cover"
                         @click="show('{{ \Illuminate\Support\Facades\Storage::url($photo->image) }}', @js($photo->title ?? ''), @js($photo->caption ?? ''))">
                    <figcaption class="p-3">
                        @if($photo->title)
                            <p class="font-serif text-lg text-night-100">{{ $photo->title }}</p>
                        @endif
                        @if($photo->caption)
                            <p class="mt-0.5 text-sm text-night-400">{{ $photo->caption }}</p>
                        @endif
                    </figcaption>
                </figure>
            @endforeach

            <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-6"
                 @click.self="close()" @keydown.escape.window="close()">
                <figure class="max-w-3xl">
                    <img :src="src" class="max-h-[80vh] rounded-lg" alt="">
                    <figcaption class="mt-3 text-center text-sm text-night-300">
                        <span class="font-serif text-lg text-night-100" x-text="title"></span>
                        <span class="block" x-text="caption"></span>
                    </figcaption>
                </figure>
            </div>
        </div>
    @endif
</div>
@endsection

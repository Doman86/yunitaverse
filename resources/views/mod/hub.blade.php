@extends('layouts.visitor')

@section('title', ucfirst($mood) . ' — ' . $text['mod_label'] . ' — ' . $siteName)

@section('content')
<section class="relative flex min-h-dvh flex-col items-center justify-center px-6 py-16 text-center
    {{ $mood === 'good' ? 'bg-gradient-to-b from-night-950 via-stone-900/40 to-night-950' : '' }}
    {{ $mood === 'normal' ? 'bg-night-950' : '' }}
    {{ $mood === 'sad' ? 'bg-gradient-to-b from-night-950 via-night-900 to-black' : '' }}">

    <a href="{{ route('mod.index') }}" class="absolute left-6 top-6 text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $text['mod_label'] }}</a>

    <p class="text-4xl">{{ $mood === 'good' ? '😊' : ($mood === 'normal' ? '😐' : '🌙') }}</p>
    <h1 class="mt-4 font-serif text-4xl uppercase tracking-[0.15em] sm:text-5xl">{{ $mood }}</h1>

    <p class="mt-4 max-w-md text-sm text-night-400">{{ $text['mod_' . $mood . '_intro'] }}</p>

    {{-- One component wraps the menu AND the surprise result, so the drawn item can appear here. --}}
    <div class="mt-12 grid w-full max-w-md grid-cols-1 gap-3" x-data="surpriseBox(@js($mood))">
        @if($mood === 'good')
            <a href="{{ route('mod.things', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">🌤 {{ $text['mod_things_label'] }}</a>
            <a href="{{ route('mod.music', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">🎧 {{ $text['mod_music_label'] }}</a>
            <button type="button" @click="draw()" :disabled="loading"
                    class="card px-8 py-4 text-left text-sm tracking-wide text-night-200 disabled:opacity-50">✦ {{ $text['mod_surprise_label'] }}</button>
        @elseif($mood === 'normal')
            <a href="{{ route('mod.things', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">🌿 {{ $text['mod_things_label'] }}</a>
            <a href="{{ route('mod.notes', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">📝 {{ $text['mod_notes_label'] }}</a>
            <a href="{{ route('mod.music', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">🎧 {{ $text['mod_music_label'] }}</a>
            <a href="{{ route('mod.photos', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">📷 {{ $text['mod_photos_label'] }}</a>
            <button type="button" @click="draw()" :disabled="loading"
                    class="card px-8 py-4 text-left text-sm tracking-wide text-night-200 disabled:opacity-50">✦ {{ $text['mod_surprise_label'] }}</button>
        @else
            <a href="{{ route('mod.music', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">🎧 {{ $text['mod_calm_music_label'] }}</a>
            <a href="{{ route('mod.notes', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">📝 {{ $text['mod_comfort_notes_label'] }}</a>
            <a href="{{ route('mod.photos', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">📷 {{ $text['mod_photos_label'] }}</a>
            <a href="{{ route('mod.things', $mood) }}" class="card px-8 py-4 text-left text-sm tracking-wide text-night-200">🌿 {{ $text['mod_little_things_label'] }}</a>
            <button type="button" @click="draw()" :disabled="loading"
                    class="card px-8 py-4 text-left text-sm tracking-wide text-night-200 disabled:opacity-50">✦ {{ $text['mod_surprise_label'] }}</button>
        @endif

        {{-- Surprise result --}}
        <div class="mt-2" x-cloak x-show="item" x-transition.opacity.duration.500ms>
            <template x-if="item">
                <div class="card p-6 text-left">
                    <p class="text-[10px] uppercase tracking-widest text-night-500" x-text="item.type"></p>
                    <template x-if="item.title"><h2 class="mt-1 font-serif text-xl text-night-100" x-text="item.title"></h2></template>
                    <template x-if="item.caption"><p class="mt-1 text-sm italic text-night-400" x-text="item.caption"></p></template>
                    <template x-if="item.image"><img :src="item.image" class="mt-3 rounded-lg border border-night-700" alt=""></template>
                    <template x-if="item.content"><p class="mt-2 whitespace-pre-line text-sm text-night-300" x-text="item.content"></p></template>
                    <template x-if="item.embed"><iframe :src="item.embed" class="mt-3 h-[352px] w-full rounded-lg" frameborder="0" allowfullscreen title="Surprise music"></iframe></template>
                    <template x-if="item.link && !item.embed"><a :href="item.link" target="_blank" rel="noopener" class="mt-2 block text-sm text-amber-200/80 underline" x-text="item.link"></a></template>
                    <button type="button" @click="draw()" :disabled="loading"
                            class="mt-4 text-[11px] uppercase tracking-widest text-night-500 transition-colors hover:text-night-300 disabled:opacity-50">
                        ✦ {{ $text['mod_surprise_again'] }}
                    </button>
                </div>
            </template>
        </div>
    </div>
</section>
@endsection

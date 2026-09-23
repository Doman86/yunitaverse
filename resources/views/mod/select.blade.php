@extends('layouts.visitor')

@section('title', $text['mod_label'] . ' — ' . $siteName)

@section('content')
<section class="starfield relative flex min-h-dvh flex-col items-center justify-center px-6 py-16 text-center">
    <a href="{{ route('home') }}" class="absolute left-6 top-6 text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>

    <p class="text-xs uppercase tracking-[0.35em] text-night-500">{{ $text['mod_label'] }}</p>
    <h1 class="mt-4 font-serif text-4xl sm:text-5xl">{{ $text['mod_question'] }}</h1>

    <div class="mt-14 grid w-full max-w-2xl grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('mod.good') }}" class="card group flex flex-col items-center gap-3 p-8">
            <span class="text-3xl transition-transform duration-300 group-hover:scale-125">😊</span>
            <span class="text-sm font-semibold uppercase tracking-[0.25em] text-night-200">Good</span>
        </a>

        <a href="{{ route('mod.normal') }}" class="card group flex flex-col items-center gap-3 p-8">
            <span class="text-3xl transition-transform duration-300 group-hover:scale-125">😐</span>
            <span class="text-sm font-semibold uppercase tracking-[0.25em] text-night-200">Normal</span>
        </a>

        <a href="{{ route('mod.sad') }}" class="card group flex flex-col items-center gap-3 p-8">
            <span class="text-3xl transition-transform duration-300 group-hover:scale-125">🌙</span>
            <span class="text-sm font-semibold uppercase tracking-[0.25em] text-night-200">Sad</span>
        </a>
    </div>
</section>
@endsection

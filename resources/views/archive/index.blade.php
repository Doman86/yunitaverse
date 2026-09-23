@extends('layouts.visitor')

@section('title', ($text['archive_label'] ?? 'Her Archive') . ' — ' . $siteName)

@section('content')
<div class="mx-auto max-w-3xl px-6 py-16">
    <header class="mb-14 text-center">
        <p class="text-xs uppercase tracking-[0.35em] text-night-500">{{ $text['archive_label'] }}</p>
        <h1 class="mt-4 font-serif text-4xl sm:text-5xl">{{ $text['archive_sub'] }}</h1>
        <p class="mt-3 text-sm text-night-400">{{ $text['archive_intro'] }}</p>
    </header>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('archive.profile') }}" class="card flex flex-col items-start gap-1 p-6 text-left">
            <span class="font-serif text-xl text-night-100">{{ $text['archive_label_profile'] }}</span>
            <span class="text-sm text-night-400">{{ $text['archive_sub_profile'] }}</span>
        </a>

        <a href="{{ route('archive.moments') }}" class="card flex flex-col items-start gap-1 p-6 text-left">
            <span class="font-serif text-xl text-night-100">{{ $text['archive_label_moments'] }}</span>
            <span class="text-sm text-night-400">{{ $text['archive_sub_moments'] }}</span>
        </a>

        <a href="{{ route('archive.journey') }}" class="card flex flex-col items-start gap-1 p-6 text-left">
            <span class="font-serif text-xl text-night-100">{{ $text['archive_label_journey'] }}</span>
            <span class="text-sm text-night-400">{{ $text['archive_sub_journey'] }}</span>
        </a>

        <a href="{{ route('archive.achievements') }}" class="card flex flex-col items-start gap-1 p-6 text-left">
            <span class="font-serif text-xl text-night-100">{{ $text['archive_label_achievements'] }}</span>
            <span class="text-sm text-night-400">{{ $text['archive_sub_achievements'] }}</span>
        </a>

        <a href="{{ route('archive.activities') }}" class="card flex flex-col items-start gap-1 p-6 text-left">
            <span class="font-serif text-xl text-night-100">{{ $text['archive_label_activities'] }}</span>
            <span class="text-sm text-night-400">{{ $text['archive_sub_activities'] }}</span>
        </a>

        <a href="{{ route('archive.favorites') }}" class="card flex flex-col items-start gap-1 p-6 text-left">
            <span class="font-serif text-xl text-night-100">{{ $text['archive_label_favorites'] }}</span>
            <span class="text-sm text-night-400">{{ $text['archive_sub_favorites'] }}</span>
        </a>
    </div>
</div>
@endsection

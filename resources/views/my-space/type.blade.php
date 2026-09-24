@extends('layouts.app')

@section('title', $label . ' — ' . __('common.my_space_area'))

@section('content')
<header class="mb-8 flex flex-wrap items-center justify-between gap-4">
    <div>
        <a href="{{ route('my-space.index') }}" class="text-xs uppercase tracking-widest text-night-600 hover:text-night-300">{{ __('my-space.back_to_my_space') }}</a>
        <h1 class="mt-2 font-serif text-3xl">{{ $label }}</h1>
    </div>
    <a href="{{ route('my-space.create', $type) }}"
       class="rounded-full bg-night-100 px-6 py-2.5 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
        {{ __('my-space.new') }}
    </a>
</header>

@if($items->isEmpty())
    @include('archive.partials.empty', ['title' => __('my-space.nothing_yet'), 'message' => __('my-space.first_one', ['type' => strtolower($label)])])
@else
    <div class="space-y-4">
        @foreach($items as $item)
            <article class="card flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
                @if($item->image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->image) }}" alt="" class="h-16 w-16 flex-shrink-0 rounded-xl border border-night-700 object-cover">
                @endif
                <div class="min-w-0 flex-1">
                    <h2 class="font-serif text-lg text-night-100">{{ $item->title ?? \Illuminate\Support\Str::limit($item->content, 60) }}</h2>
                    @if($item->caption)
                        <p class="mt-0.5 text-sm text-night-400">{{ $item->caption }}</p>
                    @endif
                    <p class="mt-1 text-[11px] uppercase tracking-widest {{ $item->status === 'published' ? 'text-emerald-300/70' : 'text-amber-300/70' }}">
                        {{ __("my-space.{$item->status}") }}
                    </p>
                </div>
                <div class="flex flex-shrink-0 items-center gap-3 text-sm">
                    <a href="{{ route('my-space.edit', [$type, $item->id]) }}" class="text-night-300 transition-colors hover:text-white">{{ __('my-space.edit') }}</a>
                    <form method="POST" action="{{ route('my-space.destroy', [$type, $item->id]) }}"
                          onsubmit="return confirm(@js(__('my-space.delete_confirm')))">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-300/80 transition-colors hover:text-rose-300">{{ __('my-space.delete') }}</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection

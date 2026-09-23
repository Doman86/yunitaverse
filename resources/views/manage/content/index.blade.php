@extends('layouts.manage')

@section('title', $label . ' — Manage')

@section('content')
<div class="mb-8 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="font-serif text-3xl">{{ $label }}</h1>
        <p class="mt-1 text-sm text-night-400">{{ $items->count() }} item(s)</p>
    </div>
    <a href="{{ route('manage.content.create', $type) }}"
       class="rounded-full bg-night-100 px-6 py-2.5 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
        + New {{ $label }}
    </a>
</div>

<form method="GET" class="mb-6 flex flex-wrap gap-3">
    <input type="text" name="q" value="{{ $q }}" placeholder="Search title, caption, content…"
           class="w-full max-w-xs rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm outline-none focus:border-night-400">
    @if(in_array('status', $fields))
        <select name="status" class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
            <option value="">All status</option>
            <option value="published" {{ $fStatus === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ $fStatus === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>
    @endif
    @if(in_array('mood', $fields))
        <select name="mood" class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
            <option value="">All moods</option>
            @foreach($moods as $mood)
                <option value="{{ $mood }}" {{ $fMood === $mood ? 'selected' : '' }}>{{ ucfirst($mood) }}</option>
            @endforeach
        </select>
    @endif
    <button type="submit" class="rounded-xl border border-night-600 px-5 py-2.5 text-sm transition-colors hover:border-night-400">Filter</button>
</form>

@if($items->isEmpty())
    <div class="card p-10 text-center text-sm text-night-400">No items found.</div>
@else
    <div class="space-y-3">
        @foreach($items as $item)
            <article class="card flex flex-col gap-4 p-5 lg:flex-row lg:items-center">
                @if($item->image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->image) }}" alt="" class="h-14 w-14 flex-shrink-0 rounded-lg border border-night-700 object-cover">
                @endif

                <div class="min-w-0 flex-1">
                    <h2 class="font-serif text-lg text-night-100">
                        {{ $item->title ?? \Illuminate\Support\Str::limit($item->content ?? '', 60) }}
                    </h2>
                    @if($item->caption)
                        <p class="mt-0.5 text-sm text-night-400">{{ $item->caption }}</p>
                    @endif
                    <div class="mt-1.5 flex flex-wrap gap-x-4 text-[11px] uppercase tracking-widest text-night-600">
                        <span class="{{ $item->status === 'published' ? 'text-emerald-300/70' : 'text-amber-300/70' }}">{{ $item->status }}</span>
                        @if(isset($item->mood))<span>mood: {{ $item->mood }}</span>@endif
                        @if(isset($item->created_by))<span>by user #{{ $item->created_by }}</span>@endif
                    </div>
                </div>

                <div class="flex flex-shrink-0 flex-wrap items-center gap-3 text-sm">
                    <form method="POST" action="{{ route('manage.content.toggle', [$type, $item->id]) }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-night-600 px-4 py-1.5 text-xs transition-colors hover:border-night-400">
                            {{ $item->status === 'published' ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                    <a href="{{ route('manage.content.edit', [$type, $item->id]) }}" class="text-night-300 transition-colors hover:text-white">Edit</a>
                    <form method="POST" action="{{ route('manage.content.destroy', [$type, $item->id]) }}" onsubmit="return confirm('Delete this item?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-300/80 transition-colors hover:text-rose-300">Delete</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection

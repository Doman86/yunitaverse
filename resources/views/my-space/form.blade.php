@extends('layouts.app')

@section('title', ($item ? __('my-space.edit_type', ['type' => $label]) : __('my-space.new_type', ['type' => $label])) . ' — ' . __('common.my_space_area'))

@section('content')
<header class="mb-8">
    <a href="{{ route('my-space.type', $type) }}" class="text-xs uppercase tracking-widest text-night-600 hover:text-night-300">← {{ $label }}</a>
    <h1 class="mt-2 font-serif text-3xl">{{ $item ? __('my-space.edit_type', ['type' => strtolower($label)]) : __('my-space.new_type', ['type' => strtolower($label)]) }}</h1>
</header>

<form method="POST"
      action="{{ $item ? route('my-space.update', [$type, $item->id]) : route('my-space.store', $type) }}"
      enctype="multipart/form-data"
      class="card max-w-xl space-y-5 p-7">
    @csrf
    @if($item)
        @method('PUT')
    @endif

    @foreach($fields as $field)
        <div>
            @if($field['type'] !== 'file')
                <label for="{{ $field['name'] }}" class="text-xs uppercase tracking-widest text-night-500">
                    {{ __($field['label']) }} @if($field['required'])<span class="text-rose-300/70">*</span>@endif
                </label>
            @endif

            @if($field['type'] === 'textarea')
                <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="4" {{ $field['required'] ? 'required' : '' }}
                    class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm outline-none focus:border-night-400">{{ old($field['name'], $item?->{$field['name']}) }}</textarea>
            @elseif($field['type'] === 'select')
                <select id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                    class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm outline-none focus:border-night-400">
                    @foreach($field['options'] as $option)
                        <option value="{{ $option }}" {{ old($field['name'], $item?->{$field['name']} ?? 'all') === $option ? 'selected' : '' }}>{{ __("admin.mood_{$option}") }}</option>
                    @endforeach
                </select>
            @elseif($field['type'] === 'file')
                <input id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="file" accept="image/jpeg,image/png,image/webp" {{ $field['required'] && ! $item ? 'required' : '' }}
                    class="mt-2 w-full text-sm text-night-400 file:mr-4 file:rounded-full file:border-0 file:bg-night-800 file:px-4 file:py-2 file:text-sm file:text-night-100">
                @if($item?->image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->image) }}" alt="" class="mt-3 h-20 rounded-lg border border-night-700 object-cover">
                @endif
            @else
                <input id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="{{ $field['type'] }}" value="{{ old($field['name'], $item?->{$field['name']}) }}" {{ $field['required'] ? 'required' : '' }}
                    class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm outline-none focus:border-night-400">
            @endif
            @if($errors->has($field['name']))
                <p class="mt-1 text-xs text-rose-300">{{ $errors->first($field['name']) }}</p>
            @endif
        </div>
    @endforeach

    <div>
        <label for="status" class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.status') }}</label>
        <select id="status" name="status" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm outline-none focus:border-night-400">
            <option value="published" {{ old('status', $item?->status ?? 'published') === 'published' ? 'selected' : '' }}>{{ __('my-space.published') }}</option>
            <option value="draft" {{ old('status', $item?->status) === 'draft' ? 'selected' : '' }}>{{ __('my-space.draft') }}</option>
        </select>
    </div>

    <button type="submit" class="w-full rounded-xl bg-night-100 py-3 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
        {{ $item ? __('my-space.save_changes') : __('my-space.create') }}
    </button>
</form>
@endsection

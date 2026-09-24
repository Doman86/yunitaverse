@extends('layouts.manage')

@section('title', ($item ? __('admin.edit_type', ['type' => $label]) : __('admin.new_type', ['type' => $label])) . ' — ' . __('common.admin_area'))

@section('content')
<h1 class="font-serif text-3xl">{{ $item ? __('admin.edit_type', ['type' => $label]) : __('admin.new_type', ['type' => $label]) }}</h1>

<form method="POST"
      action="{{ $item ? route('manage.content.update', [$type, $item->id]) : route('manage.content.store', $type) }}"
      enctype="multipart/form-data"
      class="card mt-8 max-w-xl space-y-5 p-7">
    @csrf
    @if($item)
        @method('PUT')
    @endif

    @foreach($fields as $field)
        @if(in_array($field, ['status', 'created_by']))
            @continue
        @endif

        <div>
            @php($labelText = __("admin.field_{$field}"))

            @if(in_array($field, ['mood']))
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}</label>
                <select name="{{ $field }}" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
                    @foreach($moods as $mood)
                        <option value="{{ $mood }}" {{ old($field, $item?->{$field} ?? 'all') === $mood ? 'selected' : '' }}>{{ __("admin.mood_{$mood}") }}</option>
                    @endforeach
                </select>
            @elseif($field === 'category' && $type === 'favorites')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}</label>
                <select name="category" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
                    @foreach($favoriteCategories as $key => $catLabel)
                        <option value="{{ $key }}" {{ old('category', $item?->category ?? 'things') === $key ? 'selected' : '' }}>{{ __("admin.{$catLabel}") }}</option>
                    @endforeach
                </select>
            @elseif($field === 'type')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }} {{ __('admin.auto_detected') }}</label>
                <select name="type" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
                    <option value="">{{ __('admin.auto') }}</option>
                    @foreach($surpriseTypes as $st)
                        <option value="{{ $st }}" {{ old('type', $item?->type) === $st ? 'selected' : '' }}>{{ __("admin.type_{$st}") }}</option>
                    @endforeach
                </select>
            @elseif($field === 'kind')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}</label>
                <select name="kind" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
                    @foreach(['playlist', 'track'] as $kind)
                        <option value="{{ $kind }}" {{ old('kind', $item?->kind ?? 'playlist') === $kind ? 'selected' : '' }}>{{ __("admin.kind_{$kind}") }}</option>
                    @endforeach
                </select>
            @elseif($field === 'image')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.image_hint') }}</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                       class="mt-2 w-full text-sm text-night-400 file:mr-4 file:rounded-full file:border-0 file:bg-night-800 file:px-4 file:py-2 file:text-sm file:text-night-100">
                @if($item?->image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->image) }}" alt="" class="mt-3 h-20 rounded-lg border border-night-700 object-cover">
                @endif
            @elseif($field === 'content')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}</label>
                <textarea name="content" rows="5" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('content', $item?->content) }}</textarea>
            @elseif($field === 'description')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}</label>
                <textarea name="description" rows="4" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">{{ old('description', $item?->description) }}</textarea>
            @elseif(in_array($field, ['is_featured', 'is_active']))
                <label class="flex items-center gap-3 text-sm text-night-300">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input type="checkbox" name="{{ $field }}" value="1" {{ old($field, $item?->{$field}) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-night-600 bg-night-900">
                    {{ $labelText }}
                </label>
            @elseif($field === 'date')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}</label>
                <input type="date" name="date" value="{{ old('date', $item?->date?->format('Y-m-d')) }}"
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
            @elseif($field === 'year')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}</label>
                <input type="number" name="year" min="1990" max="2100" value="{{ old('year', $item?->year) }}"
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
            @elseif($field === 'order')
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}</label>
                <input type="number" name="order" min="0" value="{{ old('order', $item?->order ?? 0) }}"
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
            @else
                <label class="text-xs uppercase tracking-widest text-night-500">{{ $labelText }}{{ $field === 'title' ? ' *' : '' }}</label>
                <input type="text" name="{{ $field }}" value="{{ old($field, $item?->{$field}) }}"
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
            @endif

            @if($errors->has($field))
                <p class="mt-1 text-xs text-rose-300">{{ $errors->first($field) }}</p>
            @endif
        </div>
    @endforeach

    <div>
        <label class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.status') }}</label>
        <select name="status" class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm">
            <option value="published" {{ old('status', $item?->status ?? 'published') === 'published' ? 'selected' : '' }}>{{ __('admin.published') }}</option>
            <option value="draft" {{ old('status', $item?->status) === 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
        </select>
    </div>

    <button type="submit" class="w-full rounded-xl bg-night-100 py-3 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
        {{ $item ? __('admin.save_changes') : __('admin.create') }}
    </button>
</form>
@endsection

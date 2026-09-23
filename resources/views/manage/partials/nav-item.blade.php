@php
    $url = route($route, $params ?? []);
    $current = url()->current();
    $active = rtrim($current, '/') === rtrim($url, '/');

    // Keep the section highlighted while on an edit page of the same content type.
    if (! $active
        && isset($params['type'])
        && request()->route()?->getName() === 'manage.content.edit'
        && request()->route('type') === $params['type']) {
        $active = true;
    }
@endphp

<a href="{{ $url }}"
   class="block rounded-xl px-3 py-2 transition-colors {{ $active ? 'bg-night-800 text-white' : 'text-night-400 hover:bg-night-800/60 hover:text-night-100' }}">
    {{ $label }}
</a>

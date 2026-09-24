@php
    $current = app()->getLocale();
    $target = $current === 'id' ? 'en' : 'id';
    // Keep the visitor on the exact same page; ?lang= is picked up by SetLocale.
    $switchUrl = url()->current() . '?' . http_build_query(array_merge(request()->query(), ['lang' => $target]));
    $flags = ['id' => '🇮🇩', 'en' => '🇬🇧'];
    $names = ['id' => __('site.lang_id'), 'en' => __('site.lang_en')];
@endphp

<span class="inline-flex items-center gap-2{{ ($variant ?? '') === 'footer' ? ' mb-3' : '' }}">
    @foreach(['id', 'en'] as $locale)
        @if($locale === $current)
            <span class="inline-flex items-center gap-1.5 rounded-full border border-night-700 bg-night-800/80 px-3 py-1 text-[11px] font-semibold tracking-wide text-night-100"
                  aria-current="true">
                <span aria-hidden="true">{{ $flags[$locale] }}</span>
                <span class="hidden sm:inline">{{ $names[$locale] }}</span>
                <span class="sm:hidden uppercase">{{ $locale }}</span>
            </span>
        @else
            <a href="{{ $switchUrl }}"
               hreflang="{{ $locale }}"
               aria-label="{{ __('admin.switch_to', ['lang' => $names[$locale]]) }}"
               class="inline-flex items-center gap-1.5 rounded-full border border-transparent px-3 py-1 text-[11px] tracking-wide text-night-500 transition-colors hover:border-night-600 hover:text-night-200">
                <span aria-hidden="true">{{ $flags[$locale] }}</span>
                <span class="hidden sm:inline">{{ $names[$locale] }}</span>
                <span class="sm:hidden uppercase">{{ $locale }}</span>
            </a>
        @endif
    @endforeach
</span>

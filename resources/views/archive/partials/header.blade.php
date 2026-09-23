<header class="mb-12">
    <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-night-600 transition-colors hover:text-night-300">← {{ $siteName }}</a>
    <h1 class="mt-4 font-serif text-4xl sm:text-5xl">@yield('archive-title')</h1>
    @yield('archive-sub')
</header>

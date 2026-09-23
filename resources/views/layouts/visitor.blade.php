<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="theme-color" content="{{ $themeColor }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    @if($ogImage)
        <meta property="og:image" content="{{ asset(\Illuminate\Support\Facades\Storage::url($ogImage)) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">

    <title>@yield('title', $siteName)</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☾</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-night-950 font-sans text-night-100 antialiased selection:bg-stone-700 selection:text-white">
    <div class="relative min-h-dvh">
        {{-- faint ambient glow --}}
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="animate-glow absolute -top-32 left-1/2 h-80 w-[36rem] -translate-x-1/2 rounded-full bg-stone-700/20 blur-3xl"></div>
        </div>

        <main class="page-enter relative z-10">
            @yield('content')
        </main>

        <footer class="relative z-10 pb-8 pt-6 text-center">
            <p class="text-xs tracking-widest text-night-600 uppercase">@yield('footer', $text['footer_note'])</p>
        </footer>
    </div>
</body>
</html>

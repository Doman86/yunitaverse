<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'My Space') — {{ $siteName }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☾</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-night-950 font-sans text-night-100 antialiased">
<header class="sticky top-0 z-40 border-b border-night-700/60 bg-night-950/80 backdrop-blur">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-5 py-3">
        <a href="{{ route('my-space.index') }}" class="font-serif text-lg tracking-wide">
            <span class="{{ $accent['text'] }}">☾</span> {{ $text['myspace_label'] }}
        </a>
        <nav class="flex items-center gap-4 text-sm text-night-400">
            <a href="{{ route('home') }}" class="transition-colors hover:text-night-100">← {{ $siteName }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="transition-colors hover:text-night-100">Logout</button>
            </form>
        </nav>
    </div>
</header>

<main class="mx-auto max-w-5xl px-5 py-8">
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-200">
            <ul class="list-inside list-disc space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>

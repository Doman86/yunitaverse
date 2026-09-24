<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ __('site.secret_unlocked') }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☾</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-dvh items-center justify-center bg-night-950 font-sans text-night-100 antialiased">
    <div class="card w-full max-w-sm p-8">
        <p class="text-center text-2xl text-night-600">☾</p>
        <h1 class="mt-3 text-center font-serif text-xl">{{ __('site.secret_hint') }}</h1>

        <div class="mt-6 text-center">
            @include('partials.lang-switch')
        </div>

        <form method="POST" action="{{ route('manage.login.attempt') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="username" class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.username') }}</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="off"
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm outline-none focus:border-night-400">
            </div>
            <div>
                <label for="password" class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.password') }}</label>
                <input id="password" name="password" type="password" required
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm outline-none focus:border-night-400">
            </div>
            @if($errors->any())
                <p class="text-sm text-rose-300">{{ $errors->first() }}</p>
            @endif
            <button type="submit" class="w-full rounded-xl bg-night-100 py-3 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
                {{ __('site.enter') }}
            </button>
        </form>
    </div>
</body>
</html>

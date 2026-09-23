<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Manage') — {{ $siteName }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>☾</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-night-900 font-sans text-night-100 antialiased">
<div class="flex min-h-dvh">
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full overflow-y-auto bg-night-950 transition-transform duration-300 lg:static lg:translate-x-0">
        <div class="flex h-full flex-col p-6">
            <a href="{{ route('manage.dashboard') }}" class="mb-8 block">
                <span class="block text-sm font-semibold tracking-widest text-white">{{ $siteName }}</span>
                <span class="block text-xs text-night-600">private management</span>
            </a>

            <nav class="flex-1 space-y-0.5 text-sm">
                <p class="px-3 pt-3 pb-1 text-[10px] uppercase tracking-widest text-night-600">Overview</p>
                @include('manage.partials.nav-item', ['route' => 'manage.dashboard', 'label' => 'Dashboard'])

                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-night-600">Her Archive</p>
                @include('manage.partials.nav-item', ['route' => 'manage.profile.edit', 'label' => 'Profile'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'moments'], 'label' => 'Moments'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'journeys'], 'label' => 'Journey'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'achievements'], 'label' => 'Achievements'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'activities'], 'label' => 'Activities'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'favorites'], 'label' => 'Favorites'])

                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-night-600">MOD</p>
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'mod-things'], 'label' => 'Things To Do'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'mod-notes'], 'label' => 'Notes'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'mod-photos'], 'label' => 'Photos'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'mod-playlists'], 'label' => 'Music'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'mod-surprises'], 'label' => 'Surprises'])

                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-night-600">Other</p>
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'notes'], 'label' => 'Archive Notes'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'memories'], 'label' => 'Memories'])
                @include('manage.partials.nav-item', ['route' => 'manage.content.index', 'params' => ['type' => 'soundtracks'], 'label' => 'Soundtrack'])
                @include('manage.partials.nav-item', ['route' => 'manage.users.index', 'label' => 'Users'])
                @include('manage.partials.nav-item', ['route' => 'manage.settings.edit', 'label' => 'Settings'])
            </nav>

            <form method="POST" action="{{ route('manage.logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl px-3 py-2.5 text-left text-sm text-night-400 transition-colors hover:bg-night-800 hover:text-white">
                    ↩ Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex items-center justify-between border-b border-night-700/60 bg-night-950 px-5 py-3 lg:hidden">
            <span class="text-sm font-semibold tracking-widest">{{ $siteName }}</span>
            <button id="sidebar-toggle" class="rounded-lg border border-night-700 px-3 py-1.5 text-sm">☰</button>
        </header>

        <main class="flex-1 p-5 sm:p-8">
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
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        if (toggle && sidebar) {
            toggle.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
        }
    });
</script>
</body>
</html>

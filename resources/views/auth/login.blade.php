@extends('layouts.visitor')

@section('title', $text['login_welcome'] . ' — ' . $siteName)

@section('content')
<section class="flex min-h-dvh items-center justify-center px-6 py-16">
    <div class="card w-full max-w-sm p-8">
        <p class="text-center text-2xl text-night-400">☾</p>
        <h1 class="mt-3 text-center font-serif text-2xl">{{ $text['login_welcome'] }}</h1>
        <p class="mt-1 text-center text-xs text-night-500">{{ $siteName }}</p>

        <form method="POST" action="{{ route('login.attempt') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label for="username" class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.username') }}</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm text-night-100 outline-none transition-colors focus:border-night-400">
            </div>
            <div>
                <label for="password" class="text-xs uppercase tracking-widest text-night-500">{{ __('admin.password') }}</label>
                <input id="password" name="password" type="password" required
                       class="mt-2 w-full rounded-xl border border-night-700 bg-night-900 px-4 py-3 text-sm text-night-100 outline-none transition-colors focus:border-night-400">
            </div>
            @if($errors->any())
                <p class="text-sm text-rose-300">{{ $errors->first() }}</p>
            @endif
            <button type="submit"
                    class="w-full rounded-xl bg-night-100 py-3 text-sm font-semibold text-night-900 transition-colors hover:bg-white">
                {{ __('site.enter') }}
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-night-600">
            <a href="{{ route('home') }}" class="transition-colors hover:text-night-300">{{ $text['login_back'] }}</a>
        </p>
    </div>
</section>
@endsection

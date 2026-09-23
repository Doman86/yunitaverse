@extends('layouts.manage')

@section('title', 'Users — Manage')

@section('content')
<h1 class="font-serif text-3xl">Users</h1>
<p class="mt-2 text-sm text-night-400">No public registration. Accounts are created here only.</p>

{{-- Add user --}}
<form method="POST" action="{{ route('manage.users.store') }}" class="card mt-8 grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-5">
    @csrf
    <input type="text" name="username" placeholder="Username" required
           class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
    <input type="text" name="name" placeholder="Display name" required
           class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
    <input type="text" name="role" placeholder="role: admin / yunita" required
           class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
    <input type="password" name="password" placeholder="Password" required
           class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
    <button type="submit" class="rounded-xl bg-night-100 px-5 py-2.5 text-sm font-semibold text-night-900 hover:bg-white">Add user</button>
</form>

<div class="mt-8 space-y-3">
    @foreach($users as $user)
        <article class="card flex flex-col gap-4 p-5 lg:flex-row lg:items-center">
            <div class="min-w-0 flex-1">
                <h2 class="font-serif text-lg text-night-100">
                    {{ $user->username }}
                    <span class="ml-2 rounded-full border border-night-600 px-2 py-0.5 text-[10px] uppercase tracking-widest text-night-400">{{ $user->role }}</span>
                </h2>
                <p class="mt-0.5 text-sm text-night-500">{{ $user->name }} @if($user->email)· {{ $user->email }}@endif</p>
            </div>

            {{-- Edit inline --}}
            <form method="POST" action="{{ route('manage.users.update', $user) }}" class="flex flex-wrap items-center gap-2">
                @csrf
                @method('PUT')
                <select name="role" class="rounded-lg border border-night-700 bg-night-900 px-3 py-2 text-sm">
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>admin</option>
                    <option value="yunita" {{ $user->role === 'yunita' ? 'selected' : '' }}>yunita</option>
                </select>
                <input type="password" name="password" placeholder="New password…" 
                       class="w-36 rounded-lg border border-night-700 bg-night-900 px-3 py-2 text-sm">
                <button type="submit" class="rounded-lg border border-night-600 px-4 py-2 text-xs hover:border-night-400">Save</button>
            </form>

            @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('manage.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-rose-300/80 hover:text-rose-300">Delete</button>
                </form>
            @endif
        </article>
    @endforeach
</div>
@endsection

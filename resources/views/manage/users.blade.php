@extends('layouts.manage')

@section('title', __('admin.users') . ' — ' . __('common.admin_area'))

@section('content')
<h1 class="font-serif text-3xl">{{ __('admin.users') }}</h1>
<p class="mt-2 text-sm text-night-400">{{ __('admin.users_sub') }}</p>

{{-- Add user --}}
<form method="POST" action="{{ route('manage.users.store') }}" class="card mt-8 grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-5">
    @csrf
    <input type="text" name="username" placeholder="{{ __('admin.username') }}" required
           class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
    <input type="text" name="name" placeholder="{{ __('admin.display_name') }}" required
           class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
    <input type="text" name="role" placeholder="{{ __('admin.role_hint') }}" required
           class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
    <input type="password" name="password" placeholder="{{ __('admin.password') }}" required
           class="rounded-xl border border-night-700 bg-night-900 px-4 py-2.5 text-sm">
    <button type="submit" class="rounded-xl bg-night-100 px-5 py-2.5 text-sm font-semibold text-night-900 hover:bg-white">{{ __('admin.add_user') }}</button>
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
                <input type="password" name="password" placeholder="{{ __('admin.new_password') }}" 
                       class="w-36 rounded-lg border border-night-700 bg-night-900 px-3 py-2 text-sm">
                <button type="submit" class="rounded-lg border border-night-600 px-4 py-2 text-xs hover:border-night-400">{{ __('admin.save') }}</button>
            </form>

            @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('manage.users.destroy', $user) }}" onsubmit="return confirm(@js(__('admin.delete_user_confirm')))">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-rose-300/80 hover:text-rose-300">{{ __('admin.delete') }}</button>
                </form>
            @endif
        </article>
    @endforeach
</div>
@endsection

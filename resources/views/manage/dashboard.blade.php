@extends('layouts.manage')

@section('title', 'Dashboard')

@section('content')
<h1 class="font-serif text-3xl">Dashboard</h1>
<p class="mt-2 text-sm text-night-400">Everything in the universe, at a glance.</p>

<div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
    @foreach($counts as $label => $count)
        <div class="card p-5">
            <p class="font-serif text-3xl text-night-100">{{ $count }}</p>
            <p class="mt-1 text-xs uppercase tracking-widest text-night-500">{{ $label }}</p>
        </div>
    @endforeach
</div>
@endsection

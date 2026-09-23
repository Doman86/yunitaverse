<div class="card mx-auto max-w-md p-10 text-center">
    <p class="text-2xl text-night-600" aria-hidden="true">☾</p>
    <p class="mt-4 font-serif text-xl italic text-night-300">{{ $title ?? 'Nothing here yet.' }}</p>
    @if(!empty($message))
        <p class="mt-2 text-sm text-night-500">{{ $message }}</p>
    @endif
</div>

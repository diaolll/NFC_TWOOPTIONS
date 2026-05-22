@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'invalid-feedback d-block']) }}>
        @foreach ((array) $messages as $message)
            <div><i class="fi fi-rr-error me-1"></i>{{ $message }}</div>
        @endforeach
    </div>
@endif

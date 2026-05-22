@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success small']) }}>
        <i class="fi fi-rr-check me-1"></i>{{ $status }}
    </div>
@endif

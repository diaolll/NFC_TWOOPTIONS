@props([
    'name',
    'show' => false,
    'maxWidth' => 'lg'
])

@php
$maxWidthClasses = [
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
];
$maxWidthClass = $maxWidthClasses[$maxWidth] ?? '';
@endphp

<div
    x-data="{
        show: @js($show),
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('modal-open');
        } else {
            document.body.classList.remove('modal-open');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    class="modal fade {{ $show ? 'show d-block' : '' }}"
    style="{{ $show ? '' : 'display: none;' }}"
    tabindex="-1"
>
    <div class="modal-dialog {{ $maxWidthClass }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

@if ($show)
    <div class="modal-backdrop fade show"></div>
@endif

@extends('layouts.gxon.login')

@section('content')
    <div class="mb-4 small text-muted">
        <i class="fi fi-rr-shield me-1"></i>{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <div class="input-group">
                <span class="input-group-text"><i class="fi fi-rr-lock"></i></span>
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="d-flex justify-content-end">
            <x-primary-button>
                <i class="fi fi-rr-check me-1"></i>{{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
@endsection

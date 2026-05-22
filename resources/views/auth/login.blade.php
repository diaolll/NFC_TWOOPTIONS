@extends('layouts.gxon.login')

@section('content')
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <div class="input-group">
                <span class="input-group-text"><i class="fi fi-rr-envelope"></i></span>
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <div class="input-group">
                <span class="input-group-text"><i class="fi fi-rr-lock"></i></span>
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">
                    <i class="fi fi-rr-check me-1"></i>{{ __('Remember me') }}
                </label>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            @if (Route::has('password.request'))
                <a class="btn btn-link me-2" href="{{ route('password.request') }}">
                    <i class="fi fi-rr-question me-1"></i>{{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                <i class="fi fi-rr-sign-in me-1"></i>{{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
@endsection

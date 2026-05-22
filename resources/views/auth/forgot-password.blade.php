@extends('layouts.gxon.login')

@section('content')
    <div class="mb-4 small text-muted">
        <i class="fi fi-rr-question me-1"></i>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <div class="input-group">
                <span class="input-group-text"><i class="fi fi-rr-envelope"></i></span>
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex justify-content-end">
            <x-primary-button>
                <i class="fi fi-rr-paper-plane me-1"></i>{{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
@endsection

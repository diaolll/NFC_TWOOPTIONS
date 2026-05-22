@extends('layouts.gxon.login')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" />
            <div class="input-group">
                <span class="input-group-text"><i class="fi fi-rr-user"></i></span>
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <div class="input-group">
                <span class="input-group-text"><i class="fi fi-rr-envelope"></i></span>
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <div class="input-group">
                <span class="input-group-text"><i class="fi fi-rr-lock"></i></span>
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="input-group">
                <span class="input-group-text"><i class="fi fi-rr-lock"></i></span>
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="d-flex justify-content-end">
            <a class="btn btn-link me-2" href="{{ route('login') }}">
                <i class="fi fi-rr-sign-in me-1"></i>{{ __('Already registered?') }}
            </a>

            <x-primary-button>
                <i class="fi fi-rr-user-plus me-1"></i>{{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
@endsection

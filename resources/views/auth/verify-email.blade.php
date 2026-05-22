@extends('layouts.gxon.login')

@section('content')
    <div class="mb-4 small text-muted">
        <i class="fi fi-rr-envelope me-1"></i>{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 small text-success">
            <i class="fi fi-rr-check me-1"></i>{{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 d-flex justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    <i class="fi fi-rr-paper-plane me-1"></i>{{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-link">
                <i class="fi fi-rr-sign-out me-1"></i>{{ __('Log Out') }}
            </button>
        </form>
    </div>
@endsection

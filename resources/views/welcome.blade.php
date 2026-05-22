@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-headings">
            <h2 class="app-page-title-title">Selamat Datang di {{ config('app.name', 'Laravel') }}</h2>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <h1 class="display-5 fw-bold mb-4">
                    Sistem Absensi NFC
                </h1>
                <p class="text-muted mb-4">
                    Selamat datang di sistem absensi menggunakan teknologi NFC.
                </p>

                @if (Route::has('login'))
                    <div class="d-flex justify-content-center gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-secondary">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

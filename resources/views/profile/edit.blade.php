@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">Profile</h2>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fi fi-rr-user me-2"></i>Informasi Profile</h5>
            </div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fi fi-rr-lock me-2"></i>Ubah Password</h5>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fi fi-rr-trash me-2"></i>Hapus Akun</h5>
            </div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection

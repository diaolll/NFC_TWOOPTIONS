@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">
                @if(auth()->user()->role === 'admin')
                    Dashboard
                @else
                    Absensi Saya
                @endif
            </h2>
        </div>
    </div>
</div>
@endsection

@section('content')
@if(auth()->user()->role === 'admin')
    {{-- ADMIN DASHBOARD --}}
    <div class="row g-3 mb-4">
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar rounded bg-primary-subtle text-primary">
                                <i class="fi fi-rr-users fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Total Mahasiswa</h6>
                            <h3 class="mb-0">{{ $stats['students'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar rounded bg-success-subtle text-success">
                                <i class="fi fi-rr-check fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Hadir Hari Ini</h6>
                            <h3 class="mb-0">{{ $stats['attendances_present'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar rounded bg-warning-subtle text-warning">
                                <i class="fi fi-rr-clock fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Total Absensi</h6>
                            <h3 class="mb-0">{{ $stats['attendances_total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar rounded bg-info-subtle text-info">
                                <i class="fi fi-rr-qrcode fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Aksi Cepat</h6>
                            <a href="{{ route('qrcode.scan') }}" class="btn btn-sm btn-primary">Scan QR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Attendances --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fi fi-rr-clock me-2"></i>Absensi Terbaru</h5>
        </div>
        <div class="card-body p-0">
            @if($recentAttendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="small"><i class="fi fi-rr-time me-1"></i>Waktu</th>
                            <th class="small"><i class="fi fi-rr-user me-1"></i>Nama</th>
                            <th class="small"><i class="fi fi-rr-id-card me-1"></i>NIM</th>
                            <th class="small"><i class="fi fi-rr-check me-1"></i>Status</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @foreach($recentAttendances as $attendance)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $attendance->scanned_at->format('H:i') }}</span>
                                <br><small class="text-muted">{{ $attendance->scanned_at->format('d M') }}</small>
                            </td>
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->user->nim ?? '-' }}</td>
                            <td>
                                @if($attendance->status === 'present')
                                    <span class="badge bg-success-subtle text-success rounded-pill"><i class="fi fi-rr-check me-1"></i>Hadir</span>
                                @elseif($attendance->status === 'late')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill"><i class="fi fi-rr-clock me-1"></i>Telat</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $attendance->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('attendances.index') }}" class="btn btn-primary btn-sm"><i class="fi fi-rr-list me-1"></i>Lihat Semua</a>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fi fi-rr-inbox fs-1 text-muted opacity-25 mb-3"></i>
                <p class="text-muted">Belum ada data absensi</p>
            </div>
            @endif
        </div>
    </div>

@else
    {{-- STUDENT DASHBOARD --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-xl rounded-circle bg-white bg-opacity-25">
                                <i class="fi fi-rr-user fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                            <p class="mb-0 opacity-75">{{ auth()->user()->nim ?? auth()->user()->email }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('qrcode.my-code') }}" class="btn btn-light btn-sm">
                                <i class="fi fi-rr-qrcode me-1"></i> QR Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar rounded bg-primary-subtle text-primary">
                                <i class="fi fi-rr-calendar fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Hari Ini</h6>
                            <h3 class="mb-0">{{ $stats['attendances_today'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar rounded bg-success-subtle text-success">
                                <i class="fi fi-rr-check fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Total</h6>
                            <h3 class="mb-0">{{ $stats['attendances_total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar rounded bg-warning-subtle text-warning">
                                <i class="fi fi-rr-clock fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Telat</h6>
                            <h3 class="mb-0 text-warning">{{ $stats['attendances_late'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance History --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fi fi-rr-clock me-2"></i>Riwayat Absensi</h5>
        </div>
        <div class="card-body p-0">
            @if($recentAttendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="small"><i class="fi fi-rr-time me-1"></i>Waktu</th>
                            <th class="small"><i class="fi fi-rr-check me-1"></i>Status</th>
                            <th class="small"><i class="fi fi-rr-qrcode me-1"></i>Metode</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @foreach($recentAttendances as $attendance)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $attendance->scanned_at->format('H:i') }}</span>
                                <br><small class="text-muted">{{ $attendance->scanned_at->format('d M') }}</small>
                            </td>
                            <td>
                                @if($attendance->status === 'present')
                                    <span class="badge bg-success-subtle text-success rounded-pill"><i class="fi fi-rr-check me-1"></i>Hadir</span>
                                @elseif($attendance->status === 'late')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill"><i class="fi fi-rr-clock me-1"></i>Telat</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $attendance->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if(str_contains($attendance->scanner_device ?? '', 'QR Code'))
                                    <i class="fi fi-rr-qrcode text-info"></i>
                                @else
                                    <i class="fi fi-rr-nfc-symbol text-primary"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fi fi-rr-inbox fs-1 text-muted opacity-25 mb-3"></i>
                <p class="text-muted mb-3">Belum ada riwayat absensi</p>
                <a href="{{ route('qrcode.scan') }}" class="btn btn-primary"><i class="fi fi-rr-qrcode me-1"></i>Scan QR Code Sekarang</a>
            </div>
            @endif
        </div>
    </div>
@endif
@endsection

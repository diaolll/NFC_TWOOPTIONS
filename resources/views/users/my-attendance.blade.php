@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">Riwayat Absensi Saya</h2>
        </div>
        <div class="app-page-title-right">
            <a href="{{ route('qrcode.my-code') }}" class="btn btn-outline-info">
                <i class="fi fi-rr-qrcode me-2"></i>Lihat QR Code
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    {{-- Stats --}}
    <div class="col-12">
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
                                <h6 class="mb-1 text-muted">Total Absensi</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}x</h3>
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
                                <h6 class="mb-1 text-muted">Hadir</h6>
                                <h3 class="mb-0 text-success">{{ $stats['present'] }}x</h3>
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
                                <h6 class="mb-1 text-muted">Terlambat</h6>
                                <h3 class="mb-0 text-warning">{{ $stats['late'] }}x</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & History --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fi fi-rr-clock me-2"></i>Filter & Riwayat</h5>
            </div>
            <div class="card-body">
                {{-- Filter --}}
                <form method="GET" action="{{ route('users.my-attendance') }}" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label small">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small">Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small d-block">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fi fi-rr-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('users.my-attendance') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fi fi-rr-cross me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>

                {{-- History --}}
                @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="small"><i class="fi fi-rr-time me-1"></i>Waktu</th>
                                <th class="small"><i class="fi fi-rr-check me-1"></i>Status</th>
                                <th class="small"><i class="fi fi-rr-qrcode me-1"></i>Metode</th>
                                <th class="small"><i class="fi fi-rr-info me-1"></i>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @foreach($attendances as $attendance)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $attendance->scanned_at->format('H:i:s') }}</span>
                                    <br><small class="text-muted">{{ $attendance->scanned_at->translatedFormat('l, d F Y') }}</small>
                                </td>
                                <td>
                                    @if($attendance->status === 'present')
                                        <span class="badge bg-success-subtle text-success rounded-pill">
                                            <i class="fi fi-rr-check me-1"></i>Hadir
                                        </span>
                                    @elseif($attendance->status === 'late')
                                        <span class="badge bg-warning-subtle text-warning rounded-pill">
                                            <i class="fi fi-rr-clock me-1"></i>Telat
                                        </span>
                                    @elseif($attendance->status === 'excused')
                                        <span class="badge bg-info-subtle text-info rounded-pill">
                                            <i class="fi fi-rr-info me-1"></i>Izin
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                            {{ $attendance->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(str_contains($attendance->scanner_device ?? '', 'QR Code'))
                                        <i class="fi fi-rr-qrcode text-info"></i>
                                        <span class="small">QR Code</span>
                                    @else
                                        <i class="fi fi-rr-nfc-symbol text-primary"></i>
                                        <span class="small">NFC</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->notes)
                                        <span class="small text-muted">{{ $attendance->notes }}</span>
                                    @else
                                        <span class="small text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                <div class="card-footer">
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-5">
                    <i class="fi fi-rr-inbox fs-1 text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Belum ada riwayat absensi</p>
                    <a href="{{ route('qrcode.scan') }}" class="btn btn-primary">
                        <i class="fi fi-rr-qrcode me-1"></i>Scan QR Code Sekarang
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

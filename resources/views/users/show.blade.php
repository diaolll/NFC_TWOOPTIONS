@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">Detail Mahasiswa</h2>
        </div>
        <div class="app-page-title-right">
            <a href="{{ route('qrcode.download', $user->id) }}" class="btn btn-outline-info">
                <i class="fi fi-rr-download me-2"></i>Download QR
            </a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                <i class="fi fi-rr-pencil me-2"></i>Edit
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    {{-- Student Info --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center">
                <div class="avatar avatar-xl rounded-circle bg-primary-subtle text-primary mx-auto mb-3">
                    <i class="fi fi-rr-user fs-3"></i>
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-2">{{ $user->nim ?? '-' }}</p>
                <p class="small text-muted mb-3">{{ $user->email }}</p>

                <div id="qrcode" class="d-inline-block p-2 bg-white border rounded mb-3"></div>

                <p class="small text-muted mb-0">
                    <i class="fi fi-rr-info me-1"></i>
                    Scan QR ini untuk absensi
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header py-2">
                <h6 class="card-title mb-0 small"><i class="fi fi-rr-chart-pie me-1"></i>Statistik</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="small">Total Absensi</td>
                            <td class="text-end"><strong>{{ $stats['total'] }}x</strong></td>
                        </tr>
                        <tr>
                            <td class="small">Hadir</td>
                            <td class="text-end"><span class="badge bg-success-subtle text-success">{{ $stats['present'] }}x</span></td>
                        </tr>
                        <tr>
                            <td class="small">Terlambat</td>
                            <td class="text-end"><span class="badge bg-warning-subtle text-warning">{{ $stats['late'] }}x</span></td>
                        </tr>
                        <tr>
                            <td class="small">Bulan Ini</td>
                            <td class="text-end"><strong>{{ $stats['this_month'] }}x</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance History --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fi fi-rr-clock me-2"></i>Riwayat Absensi</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fi fi-rr-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="small"><i class="fi fi-rr-time me-1"></i>Waktu</th>
                                <th class="small"><i class="fi fi-rr-check me-1"></i>Status</th>
                                <th class="small"><i class="fi fi-rr-qrcode me-1"></i>Metode</th>
                                <th class="small"><i class="fi fi-rr-info me-1"></i>Device</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @foreach($attendances as $attendance)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $attendance->scanned_at->format('H:i:s') }}</span>
                                    <br><small class="text-muted">{{ $attendance->scanned_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    @if($attendance->status === 'present')
                                        <span class="badge bg-success-subtle text-success rounded-pill"><i class="fi fi-rr-check me-1"></i>Hadir</span>
                                    @elseif($attendance->status === 'late')
                                        <span class="badge bg-warning-subtle text-warning rounded-pill"><i class="fi fi-rr-clock me-1"></i>Telat</span>
                                    @elseif($attendance->status === 'excused')
                                        <span class="badge bg-info-subtle text-info rounded-pill"><i class="fi fi-rr-info me-1"></i>Izin</span>
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
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 120px;">
                                        {{ \Illuminate\Support\Str::limit($attendance->scanner_device ?? '-', 20) }}
                                    </span>
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
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
const qrData = {
    type: 'attendance',
    user_id: '{{ $user->id }}',
    nim: '{{ $user->nim ?? '' }}',
    name: '{{ $user->name }}',
    generated_at: '{{ now()->toIso8601String() }}'
};

new QRCode(document.getElementById('qrcode'), {
    text: JSON.stringify(qrData),
    width: 150,
    height: 150,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});
</script>
@endpush

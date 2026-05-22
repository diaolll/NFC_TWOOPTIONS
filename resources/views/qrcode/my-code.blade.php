@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">QR Code Saya</h2>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    {{-- QR Code Card --}}
    <div class="col-lg-5">
        <div class="card mb-3 mb-lg-0 h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fi fi-rr-qrcode me-2"></i>QR Code Absensi</h5>
            </div>
            <div class="card-body text-center d-flex flex-column">

                {{-- User Info --}}
                <div class="d-flex align-items-center justify-content-center mb-4 pb-3 border-bottom">
                    <div class="avatar avatar-lg rounded-circle bg-primary-subtle text-primary me-3">
                        <i class="fi fi-rr-user fs-5"></i>
                    </div>
                    <div class="text-start">
                        <h6 class="mb-0">{{ $user->name }}</h6>
                        <small class="text-muted">{{ $user->nim ?? $user->email }}</small>
                    </div>
                </div>

                {{-- QR Code --}}
                <div class="mb-4">
                    <div id="qrcode" class="d-inline-block p-3 bg-white border rounded shadow-sm"></div>
                </div>

                {{-- Instructions --}}
                <div class="alert alert-light text-start small mb-4">
                    <i class="fi fi-rr-info me-2"></i>
                    <strong>Cara Pakai:</strong> Buka "Scan QR Code" di HP → Arahkan ke QR ini
                </div>

                {{-- Actions --}}
                <div class="d-grid gap-2 mt-auto">
                    <button onclick="downloadQRCode()" class="btn btn-primary">
                        <i class="fi fi-rr-download me-2"></i>Download QR Code
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- History --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header py-2">
                <h6 class="card-title mb-0 small"><i class="fi fi-rr-clock me-1"></i>Riwayat Absensi</h6>
            </div>
            <div class="card-body p-0">
                @if($user->attendances && $user->attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small"><i class="fi fi-rr-time me-1"></i>Waktu</th>
                                <th class="small"><i class="fi fi-rr-check me-1"></i>Status</th>
                                <th class="small"><i class="fi fi-rr-qrcode me-1"></i>Metode</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @foreach($user->attendances->sortByDesc('scanned_at')->take(5) as $attendance)
                            <tr>
                                <td>
                                    <div>{{ $attendance->scanned_at->format('H:i') }}</div>
                                    <small class="text-muted">{{ $attendance->scanned_at->format('d M') }}</small>
                                </td>
                                <td>
                                    @if($attendance->status === 'present')
                                        <span class="badge bg-success-subtle text-success rounded-pill">Hadir</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning rounded-pill">Telat</span>
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
                <div class="text-center py-3">
                    <small class="text-muted">Belum ada riwayat</small>
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

const qrContainer = document.getElementById('qrcode');
new QRCode(qrContainer, {
    text: JSON.stringify(qrData),
    width: 160,
    height: 160,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

function downloadQRCode() {
    const qrCanvas = document.querySelector('#qrcode canvas');
    const qrImg = document.querySelector('#qrcode img');
    const dataUrl = qrCanvas ? qrCanvas.toDataURL('image/png') : (qrImg ? qrImg.src : null);

    if (dataUrl) {
        const link = document.createElement('a');
        link.download = 'qrcode-{{ $user->nim ?? $user->id }}.png';
        link.href = dataUrl;
        link.click();
    }
}
</script>
@endpush

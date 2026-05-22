@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">Scan QR Code</h2>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">

        <div class="card">
            <div class="card-body">

                {{-- Status --}}
                <div id="qr-status" class="alert alert-info text-center mb-4">
                    <i class="fi fi-rr-camera fs-3 mb-2 d-block"></i>
                    <p class="mb-0">Klik tombol di bawah untuk mengaktifkan kamera</p>
                </div>

                {{-- Camera Controls --}}
                <div class="text-center mb-4">
                    <button id="start-camera-btn" onclick="startCamera()" class="btn btn-primary btn-lg px-5">
                        <i class="fi fi-rr-camera me-2"></i>Aktifkan Kamera
                    </button>
                    <button id="stop-camera-btn" onclick="stopCamera()" class="btn btn-danger btn-lg px-5 d-none">
                        <i class="fi fi-rr-stop me-2"></i>Matikan Kamera
                    </button>
                </div>

                {{-- QR Reader --}}
                <div id="qr-reader" class="mb-4 mx-auto bg-light rounded" style="max-width: 400px; min-height: 280px;">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-muted py-5">
                            <i class="fi fi-rr-qrcode fs-1 mb-3 opacity-50"></i>
                            <p>Area kamera</p>
                        </div>
                    </div>
                </div>

                {{-- Result --}}
                <div id="scan-result" class="d-none">
                    <div class="border-top pt-4">
                        <h5 class="mb-3 text-center"><i class="fi fi-rr-check me-1"></i>Hasil Scan</h5>

                        <div id="result-success" class="d-none">
                            <div class="alert alert-success">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fi fi-rr-check-circle fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="alert-heading mb-1">Absensi Berhasil!</h6>
                                        <p class="mb-0 fw-bold" id="user-name"></p>
                                        <small class="text-white" id="user-nim"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-body text-center py-2">
                                            <small class="text-muted d-block"><i class="fi fi-rr-time me-1"></i>Waktu</small>
                                            <strong id="scan-time"></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-body text-center py-2">
                                            <small class="text-muted d-block"><i class="fi fi-rr-check me-1"></i>Status</small>
                                            <strong id="scan-status" class="text-success"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="result-error" class="d-none">
                            <div class="alert alert-danger">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fi fi-rr-error fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="alert-heading mb-1">Gagal!</h6>
                                        <p class="mb-0" id="error-message"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Warning --}}
                <div id="camera-support" class="d-none">
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-start">
                            <i class="fi fi-rr-warning me-3 mt-1"></i>
                            <div>
                                <strong class="d-block">Kamera Tidak Terdeteksi</strong>
                                <small class="text-muted">Pastikan browser memiliki izin akses kamera dan gunakan HTTPS.</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Help --}}
                <div class="text-center text-muted small mt-4">
                    <p class="mb-1"><i class="fi fi-rr-mobile me-1"></i>Arahkan QR Code ke kamera</p>
                    <p class="mb-0"><i class="fi fi-rr-check me-1"></i>Support: iOS Safari, Android Chrome</p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<audio id="scan-sound" preload="auto">
    <source src="{{ asset('assets/sound/beep.mp3') }}" type="audio/mpeg">
</audio>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const API_URL = '{{ route('qrcode.scan.submit') }}';
const CSRF_TOKEN = '{{ csrf_token() }}';
let html5QrCode = null;
let isScanning = false;

function playSuccessSound() {
    const audio = document.getElementById('scan-sound');
    if (audio) {
        audio.currentTime = 0;
        audio.play().catch(e => console.log('Audio play failed:', e));
    }
}

function updateStatus(msg, type = 'info') {
    const el = document.getElementById('qr-status');
    el.className = `alert alert-${type} text-center mb-4`;
    el.innerHTML = `<i class="fi fi-rr-qrcode fs-3 mb-2 d-block"></i><p class="mb-0">${msg}</p>`;
}

function showResult(data, success = true) {
    document.getElementById('scan-result').classList.remove('d-none');
    if (success) {
        document.getElementById('result-success').classList.remove('d-none');
        document.getElementById('result-error').classList.add('d-none');
        document.getElementById('user-name').textContent = data.data.user.name;
        document.getElementById('user-nim').textContent = 'NIM: ' + (data.data.user.nim || '-');
        document.getElementById('scan-time').textContent = new Date(data.data.attendance.scanned_at).toLocaleString('id-ID');
        document.getElementById('scan-status').textContent = data.data.attendance.status === 'present' ? 'Hadir' : 'Terlambat';
        if (data.already_recorded) {
            document.getElementById('scan-status').textContent = 'Sudah Absen';
            document.getElementById('scan-status').className = 'text-warning fw-bold';
        }
    } else {
        document.getElementById('result-success').classList.add('d-none');
        document.getElementById('result-error').classList.remove('d-none');
        document.getElementById('error-message').textContent = data.message || 'Terjadi kesalahan';
    }
}

async function sendToBackend(qrData) {
    try {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ qr_data: qrData })
        });

        if (!res.ok) {
            console.error('Server error:', res.status, res.statusText);
            return { success: false, message: `Server error: ${res.status}` };
        }

        return await res.json();
    } catch (e) {
        console.error('Network error:', e);
        return { success: false, message: 'Gagal menghubungi server: ' + e.message };
    }
}

async function onScanSuccess(text) {
    if (html5QrCode) await html5QrCode.pause();
    updateStatus('Memproses...', 'primary');

    let qrData;
    try {
        qrData = JSON.parse(text);
        if (qrData.type !== 'attendance') {
            showResult({ success: false, message: 'QR Code ini bukan QR Code absensi.' }, false);
            updateStatus('QR Code tidak valid', 'danger');
            setTimeout(() => { if (isScanning) html5QrCode.resume(); }, 3000);
            return;
        }
    } catch (e) {
        showResult({ success: false, message: 'Format QR Code tidak valid.' }, false);
        updateStatus('Format salah', 'danger');
        setTimeout(() => { if (isScanning) html5QrCode.resume(); }, 3000);
        return;
    }

    const result = await sendToBackend(text);
    showResult(result, result.success);

    if (result.success) {
        playSuccessSound();
        updateStatus('Berhasil! Siap untuk scan berikutnya.', 'success');
        setTimeout(() => { if (isScanning) { html5QrCode.resume(); updateStatus('Kamera aktif...', 'primary'); } }, 3000);
    } else {
        updateStatus('Gagal', 'danger');
        setTimeout(() => { if (isScanning) { html5QrCode.resume(); updateStatus('Kamera aktif...', 'primary'); } }, 3000);
    }
}

async function startCamera() {
    if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
        alert('Kamera hanya bisa diakses via HTTPS atau localhost.');
        return;
    }
    try {
        html5QrCode = new Html5Qrcode("qr-reader");
        await html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, onScanSuccess, () => {});
        isScanning = true;
        document.getElementById('start-camera-btn').classList.add('d-none');
        document.getElementById('stop-camera-btn').classList.remove('d-none');
        updateStatus('Kamera aktif. Arahkan QR Code...', 'primary');
    } catch (e) {
        document.getElementById('camera-support').classList.remove('d-none');
        updateStatus('Tidak bisa mengakses kamera', 'danger');
    }
}

async function stopCamera() {
    if (html5QrCode && isScanning) {
        await html5QrCode.stop();
        isScanning = false;
        document.getElementById('start-camera-btn').classList.remove('d-none');
        document.getElementById('stop-camera-btn').classList.add('d-none');
        updateStatus('Kamera dimatikan', 'info');
    }
}
</script>
@endpush

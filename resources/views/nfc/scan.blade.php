@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">Scan NFC</h2>
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
                <div id="nfc-status" class="alert alert-info text-center mb-4">
                    <i class="fi fi-rr-nfc-symbol fs-3 mb-2 d-block"></i>
                    <p class="mb-0">Klik tombol di bawah untuk mengaktifkan NFC</p>
                </div>

                {{-- Button --}}
                <div class="text-center mb-4">
                    <button id="start-scan-btn" onclick="startScan()" class="btn btn-primary btn-lg px-5">
                        <i class="fi fi-rr-power me-2"></i>Aktifkan NFC
                    </button>
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

                {{-- Browser Support Warning --}}
                <div id="browser-support" class="d-none">
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-start">
                            <i class="fi fi-rr-warning me-3 mt-1"></i>
                            <div>
                                <strong class="d-block">Browser Tidak Mendukung</strong>
                                <small class="text-muted">Web NFC API hanya didukung di <strong>Android Chrome 89+</strong>. iOS Safari & browser lain tidak mendukung.</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Help --}}
                <div class="text-center text-muted small mt-4">
                    <p class="mb-1"><i class="fi fi-rr-mobile me-1"></i> Dekatkan kartu ke bagian belakang HP (≤ 4 cm)</p>
                    <p class="mb-0"><i class="fi fi-rr-info me-1"></i> Hanya support Android Chrome 89+</p>
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

<script>
const API_URL = '{{ route('nfc.scan.submit') }}';
const CSRF_TOKEN = '{{ csrf_token() }}';
let ndef = null, isScanning = false;

function playSuccessSound() {
    const audio = document.getElementById('scan-sound');
    if (audio) {
        audio.currentTime = 0;
        audio.play().catch(e => console.log('Audio play failed:', e));
    }
}

function updateStatus(msg, type = 'info') {
    const el = document.getElementById('nfc-status');
    const icons = { info: 'fi-rr-nfc-symbol', success: 'fi-rr-check-circle', error: 'fi-rr-error', scanning: 'fi fi-rr-spinner fa-spin' };
    el.className = `alert alert-${type} text-center mb-4`;
    el.innerHTML = `<i class="fi ${icons[type]} fs-3 mb-2 d-block"></i><p class="mb-0">${msg}</p>`;
}

function showResult(data, success = true) {
    document.getElementById('scan-result').classList.remove('d-none');
    if (success) {
        document.getElementById('result-success').classList.remove('d-none');
        document.getElementById('result-error').classList.add('d-none');
        document.getElementById('user-name').textContent = data.data.user.name;
        document.getElementById('user-nim').textContent = 'NIM: ' + (data.data.user.nim || '-');
        document.getElementById('scan-time').textContent = data.data.attendance.scanned_at;
        document.getElementById('scan-status').textContent = data.data.attendance.status === 'present' ? 'Hadir' : 'Terlambat';
    } else {
        document.getElementById('result-success').classList.add('d-none');
        document.getElementById('result-error').classList.remove('d-none');
        document.getElementById('error-message').textContent = data.message || 'Terjadi kesalahan';
    }
}

async function sendToBackend(serial, data) {
    try {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ serial_number: serial, data: data, scanner_device: navigator.userAgent })
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

async function startScan() {
    if (!('NDEFReader' in window)) {
        document.getElementById('browser-support').classList.remove('d-none');
        updateStatus('Browser tidak mendukung Web NFC', 'danger');
        return;
    }

    try {
        ndef = new NDEFReader();
        await ndef.scan();
        isScanning = true;

        const btn = document.getElementById('start-scan-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fi fi-rr-spinner fa-spin me-2"></i>NFC Aktif';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-success');
        updateStatus('NFC aktif. Dekatkan kartu...', 'scanning');

        ndef.addEventListener('reading', async (event) => {
            let content = '';
            for (const record of message.records) {
                content += new TextDecoder(record.encoding || 'utf-8').decode(record.data);
            }

            const result = await sendToBackend(event.serialNumber, content);
            showResult(result, result.success);

            if (result.success) {
                playSuccessSound();
                updateStatus('Berhasil! Siap untuk scan berikutnya.', 'success');
                setTimeout(() => updateStatus('NFC aktif...', 'scanning'), 3000);
            } else {
                updateStatus('Gagal', 'error');
                setTimeout(() => updateStatus('NFC aktif...', 'scanning'), 3000);
            }
        });

    } catch (e) {
        updateStatus('Error: ' + e.message, 'error');
        const btn = document.getElementById('start-scan-btn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fi fi-rr-power me-2"></i>Aktifkan NFC';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-primary');
    }
}
</script>
@endpush

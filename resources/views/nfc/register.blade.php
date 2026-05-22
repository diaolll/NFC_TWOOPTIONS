@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">Registrasi Kartu NFC</h2>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fi fi-rr-id-card me-2"></i>Registrasi Kartu NFC Baru</h5>
            </div>
            <div class="card-body">

                <form id="register-form" onsubmit="handleRegister(event)">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label"><i class="fi fi-rr-user me-1"></i>Pilih Mahasiswa <span class="text-danger">*</span></label>
                        <select name="user_id" required class="form-select">
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach(\App\Models\User::where('role', 'student')->get() as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} ({{ $student->nim }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fi fi-rr-nfc-symbol me-1"></i>Serial Number Kartu NFC <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="serial_number" required
                                placeholder="Scan kartu atau ketik manual"
                                class="form-control font-monospace">
                            <button type="button" onclick="scanForSerial()" class="btn btn-success">
                                <i class="fi fi-rr-nfc-symbol"></i>
                            </button>
                        </div>
                        <small class="text-muted">
                            <i class="fi fi-rr-info me-1"></i>
                            Format: XX:XX:XX:XX:XX:XX (hex dipisahkan colon)
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fi fi-rr-file me-1"></i>Data Tambahan (Opsional)</label>
                        <textarea name="data" rows="3"
                            placeholder="Data tambahan yang disimpan di kartu"
                            class="form-control"></textarea>
                    </div>

                    <div id="scan-area" class="d-none mb-3 p-3 bg-primary-subtle rounded">
                        <p class="text-primary text-center mb-3"><i class="fi fi-rr-nfc-symbol me-1"></i>Dekatkan kartu NFC...</p>
                        <div class="text-center">
                            <i class="fi fi-rr-spinner fa-spin text-primary fs-3"></i>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fi fi-rr-disk me-2"></i>Simpan Registrasi
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fi fi-rr-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>

                <div id="result-message" class="d-none mt-3"></div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
let ndef = null;

async function scanForSerial() {
    if (!('NDEFReader' in window)) {
        alert('Browser tidak mendukung Web NFC. Gunakan Android Chrome 89+.');
        return;
    }
    const scanArea = document.getElementById('scan-area');
    scanArea.classList.remove('d-none');
    try {
        ndef = new NDEFReader();
        await ndef.scan();
        ndef.addEventListener('reading', (event) => {
            document.querySelector('input[name="serial_number"]').value = event.serialNumber;
            scanArea.classList.add('d-none');
        });
    } catch (error) {
        alert('Error: ' + error.message);
        scanArea.classList.add('d-none');
    }
}

async function handleRegister(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const resultDiv = document.getElementById('result-message');

    try {
        const res = await fetch('{{ route('nfc.register') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                user_id: formData.get('user_id'),
                serial_number: formData.get('serial_number'),
                data: formData.get('data') || null
            })
        });
        const result = await res.json();

        resultDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
        if (result.success) {
            resultDiv.classList.add('alert-success');
            resultDiv.innerHTML = `<i class="fi fi-rr-check-circle me-2"></i><strong>Berhasil!</strong> ${result.message}`;
            form.reset();
        } else {
            resultDiv.classList.add('alert-danger');
            resultDiv.innerHTML = `<i class="fi fi-rr-error me-2"></i><strong>Gagal!</strong> ${result.message}`;
        }
    } catch (error) {
        resultDiv.classList.remove('d-none');
        resultDiv.classList.add('alert-danger');
        resultDiv.innerHTML = `<i class="fi fi-rr-error me-2"></i>Error: ${error.message}`;
    }
}
</script>
@endpush

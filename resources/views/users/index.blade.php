@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">Daftar Mahasiswa</h2>
        </div>
        <div class="app-page-title-right">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fi fi-rr-plus me-2"></i>Tambah Mahasiswa
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fi fi-rr-users me-2"></i>Mahasiswa</h5>
            <form class="d-flex" method="GET" action="{{ route('users.index') }}">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/NIM/email..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                    <i class="fi fi-rr-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="small">#</th>
                        <th class="small"><i class="fi fi-rr-user me-1"></i>Nama</th>
                        <th class="small"><i class="fi fi-rr-id-card me-1"></i>NIM</th>
                        <th class="small"><i class="fi fi-rr-envelope me-1"></i>Email</th>
                        <th class="small"><i class="fi fi-rr-phone me-1"></i>Telepon</th>
                        <th class="small"><i class="fi fi-rr-check me-1"></i>Absensi</th>
                        <th class="small text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($students as $index => $student)
                    <tr>
                        <td>{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm rounded-circle bg-primary-subtle text-primary me-2">
                                    <i class="fi fi-rr-user fs-6"></i>
                                </div>
                                <span class="fw-bold">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td><code class="small">{{ $student->nim ?? '-' }}</code></td>
                        <td>
                            <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                {{ $student->email }}
                            </span>
                        </td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary rounded-pill">
                                {{ $student->attendances()->count() }}x
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('users.show', $student) }}" class="btn btn-outline-primary" title="Detail">
                                    <i class="fi fi-rr-eye"></i>
                                </a>
                                <a href="{{ route('qrcode.download', $student->id) }}" class="btn btn-outline-info" title="Download QR">
                                    <i class="fi fi-rr-qrcode"></i>
                                </a>
                                <a href="{{ route('users.edit', $student) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fi fi-rr-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                    onclick="confirmDelete('{{ route('users.destroy', $student) }}')">
                                    <i class="fi fi-rr-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="card-footer">
            {{ $students->appends(request()->query())->links() }}
        </div>
        @endif

        @if($students->count() === 0)
        <div class="text-center py-5">
            <i class="fi fi-rr-users fs-1 text-muted opacity-25 mb-3"></i>
            <p class="text-muted">Belum ada data mahasiswa</p>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="fi fi-rr-plus me-1"></i>Tambah Mahasiswa
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url) {
    if (confirm('Yakin ingin menghapus mahasiswa ini? Data absensi juga akan dihapus.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

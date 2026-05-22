@extends('layouts.gxon.main')

@section('content-header')
<div class="app-page-title">
    <div class="app-page-title-wrapper">
        <div class="app-page-title-left">
            <h2 class="app-page-title-title">Daftar Absensi</h2>
        </div>
    </div>
</div>
@endsection

@section('content')
{{-- Filter Form --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('attendances.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Filter Tanggal</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Filter Mahasiswa</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">Semua Mahasiswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('user_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->nim ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Filter Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>Izin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small d-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fi fi-rr-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fi fi-rr-cross me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar rounded bg-success-subtle text-success">
                            <i class="fi fi-rr-calendar-day fs-5"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Hari Ini</h6>
                        <h3 class="mb-0">{{ $stats['today'] }}</h3>
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
                        <h6 class="mb-1">Hadir</h6>
                        <h3 class="mb-0 text-success">{{ $stats['present'] }}</h3>
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
                        <h6 class="mb-1">Terlambat</h6>
                        <h3 class="mb-0 text-warning">{{ $stats['late'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fi fi-rr-list me-2"></i>Semua Data Absensi</h5>
            <button onclick="location.reload()" class="btn btn-sm btn-outline-primary">
                <i class="fi fi-rr-refresh me-1"></i> Refresh
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="small">#</th>
                        <th class="small"><i class="fi fi-rr-time me-1"></i>Waktu</th>
                        <th class="small"><i class="fi fi-rr-user me-1"></i>Nama</th>
                        <th class="small"><i class="fi fi-rr-envelope me-1"></i>Email</th>
                        <th class="small"><i class="fi fi-rr-id-card me-1"></i>NIM</th>
                        <th class="small"><i class="fi fi-rr-nfc-symbol me-1"></i>Kartu</th>
                        <th class="small"><i class="fi fi-rr-check me-1"></i>Status</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($attendances as $index => $attendance)
                    <tr>
                        <td>{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $index + 1 }}</td>
                        <td>
                            <span class="fw-bold">{{ $attendance->scanned_at->format('H:i:s') }}</span>
                            <br><small class="text-muted">{{ $attendance->scanned_at->format('d M Y') }}</small>
                        </td>
                        <td>{{ $attendance->user->name }}</td>
                        <td>
                            <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                {{ $attendance->user->email }}
                            </span>
                        </td>
                        <td>{{ $attendance->user->nim ?? '-' }}</td>
                        <td><code class="small">{{ \Illuminate\Support\Str::limit($attendance->nfcCard?->serial_number ?? '-', 15) }}</code></td>
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
    </div>
</div>
@endsection

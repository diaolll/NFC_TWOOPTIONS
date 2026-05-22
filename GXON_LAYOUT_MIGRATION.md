# Migrasi Layout GXON - Dokumentasi

## Ringkasan

Migrasi semua view template untuk menggunakan layout `@layouts/gxon` dari template GXON HR Management Admin Dashboard.

## Tanggal

22 Mei 2026

## Files yang Dimodifikasi

### 1. View Templates (12 files)

#### Menggunakan `layouts.gxon.main` (Authenticated Pages)

| File | Deskripsi |
|------|-----------|
| `resources/views/dashboard.blade.php` | Halaman dashboard utama |
| `resources/views/nfc/scan.blade.php` | Halaman scan NFC |
| `resources/views/nfc/register.blade.php` | Halaman registrasi kartu NFC |
| `resources/views/attendances/index.blade.php` | Daftar data absensi |
| `resources/views/profile/edit.blade.php` | Halaman edit profile |
| `resources/views/welcome.blade.php` | Halaman welcome |

#### Menggunakan `layouts.gxon.login` (Auth Pages)

| File | Deskripsi |
|------|-----------|
| `resources/views/auth/login.blade.php` | Halaman login |
| `resources/views/auth/register.blade.php` | Halaman register |
| `resources/views/auth/forgot-password.blade.php` | Halaman lupa password |
| `resources/views/auth/reset-password.blade.php` | Halaman reset password |
| `resources/views/auth/verify-email.blade.php` | Halaman verifikasi email |
| `resources/views/auth/confirm-password.blade.php` | Halaman konfirmasi password |

### 2. Layout Files

| File | Perubahan |
|------|-----------|
| `resources/views/layouts/gxon/main.blade.php` | Tambahkan CSRF token, update locale |
| `resources/views/layouts/gxon/head.blade.php` | Tambahkan CSRF token meta tag |
| `resources/views/layouts/gxon/login.blade.php` | Rewrite sebagai layout template yang proper |
| `resources/views/layouts/gxon/sidebar.blade.php` | Dibuat baru untuk NFC |
| `resources/views/layouts/gxon/navbar.blade.php` | Disesuaikan untuk NFC |
| `resources/views/layouts/gxon/footer.blade.php` | Update route dan copyright |

### 3. Assets

Copy seluruh folder `assets/` dari project lama:
- **Source:** `/Users/diaul/Downloads/project pwbf smt 3/lararshp.rev/public/assets/`
- **Destination:** `/Users/diaul/NFC-SMT4/public/assets/`

## Struktur Layout GXON

### `layouts.gxon.main`
```blade
@extends('layouts.gxon.main')

@section('content-header')
    <!-- Breadcrumb/Title -->
@endsection

@section('content')
    <!-- Main Content -->
@endsection

@push('scripts')
    <!-- Additional Scripts -->
@endpush
```

### `layouts.gxon.login`
```blade
@extends('layouts.gxon.login')

@section('content')
    <!-- Form Content -->
@endsection
```

## Menu Sidebar NFC

```
├── Dashboard
├── Scan NFC
├── Registrasi Kartu
├── Data Absensi
└── Profile
```

## Assets yang Diperlukan

```
public/assets/
├── css/
│   └── styles.css
├── js/
│   ├── appSettings.js
│   └── main.js
├── libs/
│   ├── global/
│   ├── bootstrap-select/
│   ├── fontawesome/
│   ├── flaticon/
│   ├── lucide/
│   ├── node-waves/
│   └── simplebar/
├── images/
│   ├── favicon.png
│   ├── logo.svg
│   ├── logo-full.svg
│   ├── logo-full-white.svg
│   └── avatar/
└── scss/
```

## Catatan Penting

### 1. Dependency yang Dihapus
- `roles` relationship - template asli dari RSHP menggunakan system roles, NFC tidak memilikinya
- `session('user_role')` - diganti dengan pengecekan langsung ke `Auth::user()->role`

### 2. Perubahan Field User
| Asli | Baru |
|------|------|
| `Auth::user()->nama` | `Auth::user()->name` |

### 3. Route yang Diganti
| Asli | Baru |
|------|------|
| `route('site.home')` | `route('dashboard')` |
| `route('admin.*')` | `route('nfc.*')`, `route('attendances.*')` |

## Troubleshooting

### Error: "Call to a member function first() on null"
**Penyebab:** Sidebar mencoba mengakses `$user->roles->first()` yang tidak ada.

**Solusi:** Rewrite sidebar untuk NFC tanpa dependency roles.

### Tampilan Polos/Tanpa Styling
**Penyebab:** Folder `public/assets/` tidak ada.

**Solusi:** Copy assets folder dari template GXON asli.

## Files Tidak Dimodifikasi

Berikut ini TIDAK diubah karena merupakan reusable components:
- `resources/views/components/*`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/guest.blade.php`
- `resources/views/layouts/navigation.blade.php`
- `resources/views/profile/partials/*`

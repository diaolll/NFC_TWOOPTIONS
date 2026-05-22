# Sistem Absensi NFC - Web NFC API & Laravel

Aplikasi sistem absensi berbasis NFC menggunakan Laravel dan Web NFC API.

## Persyaratan

- PHP 8.2+
- Composer
- MySQL / SQLite
- Android Chrome 89+ (untuk scanning NFC)
- Kartu NFC (NDEF compatible)

## Instalasi

1. Install dependencies:
```bash
composer install
npm install
```

2. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Setup database di `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nfc_attendance
DB_USERNAME=root
DB_PASSWORD=
```

4. Jalankan migration dan seeder:
```bash
php artisan migrate
php artisan db:seed
```

5. Jalankan server:
```bash
php artisan serve
```

6. Akses di browser:
- Dashboard: `http://localhost:8000`
- Scan NFC: `http://localhost:8000/nfc/scan`
- Registrasi Kartu: `http://localhost:8000/nfc/register`
- Daftar Absensi: `http://localhost:8000/attendances`

## Cara Menggunakan

### 1. Registrasi Kartu NFC

1. Buka menu "Registrasi Kartu"
2. Pilih mahasiswa dari dropdown
3. Scan kartu NFC atau ketik serial number manual
4. Klik "Simpan Registrasi"

### 2. Scan Absensi

1. Buka menu "Scan NFC" di HP Android (Chrome)
2. Klik "Aktifkan NFC"
3. Dekatkan kartu NFC ke bagian belakang HP (≤ 4 cm)
4. Data absensi akan tersimpan otomatis

### 3. Melihat Daftar Absensi

1. Buka menu "Absensi"
2. Lihat riwayat absensi semua mahasiswa

## Testing dengan ngrok (Untuk Android)

```bash
# Terminal 1: Jalankan Laravel
php artisan serve --port=8000

# Terminal 2: Jalankan ngrok
ngrok http 8000

# Buka URL ngrok di HP Android Chrome
# Contoh: https://abc123.ngrok.io/nfc/scan
```

## Akun Default

- **Admin**: admin@nfc.local / admin123
- **Password Mahasiswa**: password

## Struktur Database

### users
- id, name, email, password, nim, phone, role

### nfc_cards
- id, user_id, serial_number, data, is_active, issued_at

### attendances
- id, user_id, nfc_card_id, status, scanned_at, scanner_device, notes

## Catatan Penting

- Web NFC API **HANYA** berfungsi di **Android Chrome 89+**
- iOS Safari **TIDAK** mendukung Web NFC
- Wajib menggunakan **HTTPS** atau **localhost**
- Scan NFC harus di-trigger dari **user gesture** (klik tombol)

## Debugging Remote (Chrome DevTools)

1. Aktifkan USB Debugging di HP Android
2. Hubungkan HP ke laptop via USB
3. Buka `chrome://inspect/#devices` di laptop
4. Klik "inspect" pada tab Chrome di HP
5. Debug seperti biasa dari laptop

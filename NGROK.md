# Ngrok HTTPS Setup Guide

## Quick Start

### 1. Install Ngrok (jika belum)

**macOS:**
```bash
brew install ngrok
```

**Linux:**
```bash
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null
echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list
sudo apt update && sudo apt install ngrok
```

### 2. Start Laravel Server

```bash
cd /Users/diaul/NFC-SMT4
php artisan serve --port=8000
```

### 3. Start Ngrok (di terminal lain)

```bash
ngrok http 8000
```

Output akan seperti:
```
Session Status                online
Account                       user@email.com
Version                       3.x.x
Region                        United States (us)
Forwarding                    https://abcd-1234.ngrok-free.app -> http://localhost:8000
```

### 4. Update .env

Copy URL HTTPS dari ngrok dan update `.env`:

```env
APP_URL=https://abcd-1234.ngrok-free.app

# Jika menggunakan Sanctum/API
SANCTUM_STATEFUL_DOMAINS=abcd-1234.ngrok-free.app
```

### 5. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Testing dari HP

| Fitur | Android | iOS |
|-------|---------|-----|
| NFC Scan | Chrome 89+ | ❌ Tidak support |
| QR Code Scan | Chrome/Safari | Safari/Chrome |
| Input Manual | Semua browser | Semua browser |

### URL untuk Testing:
- **Dashboard:** `https://abcd-1234.ngrok-free.app/dashboard`
- **Scan NFC:** `https://abcd-1234.ngrok-free.app/nfc/scan`
- **Registrasi:** `https://abcd-1234.ngrok-free.app/nfc/register`

---

## Tips

1. **URL ngrok berubah setiap restart** - harus update .env lagi
2. **Free tier ngrok** - URL berubah random, bisa upgrade ke paid untuk custom domain
3. **CSP Issues** - jika ada error Content Security Policy, tambahkan ngrok domain ke trusted domains

---

## Troubleshooting

### NFC tidak jalan di HP
- Pastikan pakai **URL HTTPS** bukan HTTP
- Pastikan browser: **Android Chrome 89+**
- Cek console browser untuk error

### Session/Cookie issues
- Tambahkan ke `.env`:
  ```env
  SESSION_DOMAIN=.ngrok-free.app
  SANCTUM_STATEFUL_DOMAINS=your-url.ngrok-free.app
  ```

### Mixed Content Warning
- Pastikan semua asset menggunakan HTTPS
- Laravel biasanya auto-handle ini dengan `asset()` helper

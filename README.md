# Website Ditres PPA/PPO POLDA NTB

Website resmi layanan PPA/PPO (Perlindungan Perempuan dan Anak / Perlindungan Perempuan dan Orang) POLDA NTB, terdiri dari **landing page publik** dan **admin panel**.

---

## ✨ Fitur Utama

### Publik
- Landing page: Home, Organisasi, Layanan Masyarakat, Informasi, Galeri
- Form aduan masyarakat → simpan ke DB → redirect WhatsApp hotline
- Form konsultasi online
- Proteksi anti-spam: Cloudflare Turnstile + rate limiting

### Admin Panel
- **Dashboard** dengan grafik dan KPI aduan & konsultasi
- **Operasional**: Kelola aduan, konsultasi, pantau lokasi, audit log aktivitas
- **Konten Publik**: Berita/event, pimpinan, dokumen UU (PDF), testimoni, FAQ *(khusus Super Admin)*
- **Sistem**: Pengaturan situs, Manajemen Pengguna *(khusus Super Admin)*
- Export laporan: Excel (`.xlsx`) dan PDF (`.pdf`)

### Keamanan
- **2FA via Email OTP** — setiap login dikirim OTP 6 digit ke email admin via Resend
- **RBAC (Role-Based Access Control)** menggunakan Spatie Laravel Permission
- Dua role: `super_admin` (akses penuh) dan `admin` (hanya fitur operasional)
- Data sensitif aduan (`nik`, `no_hp`, `email`) terenkripsi di database
- Audit log setiap perubahan konten oleh admin

---

## 🏗️ Stack Teknologi

| Layer | Teknologi |
|---|---|
| Framework | Laravel 12 |
| Database | SQLite (lokal & Docker) |
| Frontend | Blade + Tailwind CSS + DaisyUI + Alpine.js |
| Animasi | Swiper, GLightbox, AOS |
| Auth | Laravel Breeze + 2FA OTP + Spatie Permission |
| Email | Resend (via `resend/resend-laravel`) |
| Export | maatwebsite/excel + barryvdh/laravel-dompdf |
| Deployment | Docker (PHP 8.2-FPM + Nginx) |

---

## 🚀 Instalasi Lokal

### 1. Prasyarat
- PHP 8.2+ dengan ekstensi: `pdo_sqlite`, `zip`, `gd`, `intl`, `bcmath`
- Composer 2
- Node.js 18+ + npm
- **Windows**: pastikan `C:\php\cacert.pem` dikonfigurasi di `php.ini` untuk SSL cURL (lihat [panduan SSL Windows](#-fix-ssl-curl-di-windows))

### 2. Setup

```bash
git clone https://github.com/RikiSanjayaa/web-ppa.git
cd web-ppa

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link

npm install
npm run dev

# Terminal baru:
php artisan serve
```

Akses di: `http://127.0.0.1:8000`

### 3. Konfigurasi ENV Lokal

Tambahkan ke `.env`:

```env
# Email OTP via Resend
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@domain-anda.com"
RESEND_API_KEY=re_xxxxxxxxxxxxxxxx

# Hotline WhatsApp
HOTLINE_WA_NUMBER=6281234567890
```

### 4. Buat Akun Admin Pertama

```bash
# Super Admin (akses penuh):
php artisan make:admin \
  --name="Nama Admin" \
  --email="admin@domain.com" \
  --password="Password123" \
  --role="super_admin"

# Admin biasa (hanya operasional):
php artisan make:admin \
  --name="Nama Operator" \
  --email="operator@domain.com" \
  --password="Password123" \
  --role="admin"
```

---

## 🔐 Sistem Autentikasi & Akses

### Alur Login (2FA)
```
Input Email + Password
        ↓
  Validasi Kredensial
        ↓
  OTP dikirim ke Email
        ↓
  Input OTP (valid 5 menit, maks 5 percobaan)
        ↓
     Login Berhasil
```

### Role & Akses

| Fitur | Super Admin | Admin |
|---|:---:|:---:|
| Dashboard | ✅ | ✅ |
| Aduan & Konsultasi | ✅ | ✅ |
| Pantau Lokasi | ✅ | ✅ |
| Audit Log | ✅ | ✅ |
| Export Laporan | ✅ | ✅ |
| Konten Publik (Berita, Dokumen, dll) | ✅ | ❌ |
| Pengaturan Situs | ✅ | ❌ |
| Manajemen Pengguna | ✅ | ❌ |

### Manajemen Pengguna (UI)

Super Admin dapat mengelola pengguna di **Sistem → Manajemen Pengguna**:
- Lihat daftar semua admin
- Tambah pengguna baru (pilih role)
- Aktifkan / Nonaktifkan pengguna
- Hapus pengguna

---

## 🐳 Deploy ke Server (Docker)

### Prasyarat Server
- Docker & Docker Compose terinstall
- File `.env.production` sudah dikonfigurasi (lihat bagian bawah)

### Langkah Deploy Pertama Kali

```bash
# 1. Clone repo
git clone https://github.com/RikiSanjayaa/web-ppa.git
cd web-ppa

# 2. Siapkan .env dari template production
cp .env.production .env
# WAJIB: isi APP_KEY dan RESEND_API_KEY di .env

# 3. Build dan jalankan container
docker compose -f compose.prod.yaml build
docker compose -f compose.prod.yaml up -d

# 4. Buat akun Super Admin pertama
docker compose -f compose.prod.yaml exec app php artisan make:admin \
  --name="Nama Admin" \
  --email="admin@domain.com" \
  --password="PasswordKuat!" \
  --role="super_admin"
```

> **Otomatis saat container start:** migrate, RoleSeeder, storage link, dan optimize.

### Update (Deploy Ulang setelah Update Kode)

```bash
git pull origin main

# Jika ada perubahan package PHP/JS atau Dockerfile:
docker compose -f compose.prod.yaml build --no-cache app

# Restart container
docker compose -f compose.prod.yaml up -d
```

### Konfigurasi `.env` Production

```env
APP_NAME="DITRES PPA PPO POLDA NTB"
APP_ENV=production
APP_KEY=                          # generate: php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://ditresppappo-ntb.id

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

SESSION_DOMAIN=ditresppappo-ntb.id

MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@ditresppappo-ntb.id"
MAIL_FROM_NAME="DITRES PPA PPO POLDA NTB"
RESEND_API_KEY=re_xxxxxxxxxxxxxxxx   # dari resend.com/api-keys

HOTLINE_WA_NUMBER=6281234567890

# Opsional: Cloudflare Turnstile untuk proteksi form
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=...
TURNSTILE_SECRET_KEY=...
```

---

## 🔧 Fix SSL cURL di Windows

Jika muncul error `cURL error 60: SSL certificate problem` saat login:

```powershell
# 1. Download CA bundle
Invoke-WebRequest -Uri "https://curl.se/ca/cacert.pem" -OutFile "C:\php\cacert.pem"

# 2. Tambahkan ke C:\php\php.ini:
#    curl.cainfo="C:\php\cacert.pem"
#    openssl.cafile="C:\php\cacert.pem"

# 3. Restart php artisan serve
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 📁 Struktur Direktori Penting

```
app/
├── Console/Commands/MakeAdmin.php      # Artisan: buat akun admin
├── Http/Controllers/
│   ├── Admin/UserManagementController.php  # CRUD pengguna
│   └── Auth/OtpVerifyController.php        # Verifikasi OTP
├── Mail/OtpMail.php                    # Email OTP
├── Services/OtpService.php             # Generate & verify OTP
└── Models/
    ├── User.php                         # HasRoles trait
    └── LoginOtp.php                     # Model tabel OTP

database/seeders/RoleSeeder.php          # Buat role & assign ke user

docker/
├── php/Dockerfile                       # Multi-stage build (Node + PHP)
├── php/entrypoint.sh                    # Setup otomatis saat start
└── nginx/default.conf

resources/views/
├── admin/users/                         # UI manajemen pengguna
└── auth/otp-verify.blade.php            # Halaman input OTP
```

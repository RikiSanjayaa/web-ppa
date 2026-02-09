# Website PPA/PPO

Website landing page + admin CMS untuk layanan PPA/PPO dengan fitur:

- Landing page publik: `home`, `organisasi`, `layanan masyarakat`, `informasi`, `galeri`
- Form aduan publik -> simpan log DB -> redirect otomatis ke WhatsApp hotline
- Admin panel lengkap: aduan, berita/event, pimpinan, dokumen UU (PDF), galeri, testimoni, pengaturan situs
- Export log aduan: Excel (`.xlsx`) dan PDF (`.pdf`)
- Proteksi anti-spam form: Cloudflare Turnstile
- Audit log aktivitas admin untuk perubahan konten utama

## Stack

- Laravel 12
- MySQL/MariaDB (production) / SQLite (local default)
- Blade + Tailwind CSS + DaisyUI + Alpine.js
- Swiper + GLightbox + AOS
- Laravel Breeze (auth admin)
- maatwebsite/excel + barryvdh/laravel-dompdf

## Struktur Fitur

### Publik

- `GET /` Home
- `GET /organisasi`
- `GET /layanan-masyarakat`
- `POST /aduan`
- `GET /informasi`
- `GET /informasi/{slug}`
- `GET /informasi/dokumen/{document}/download`
- `GET /galeri`

### Admin (middleware `auth` + `admin`)

- `GET /admin`
- `GET /admin/aduan`
- `GET /admin/aduan/{complaint}`
- `PATCH /admin/aduan/{complaint}/status`
- `GET /admin/aduan/export/excel`
- `GET /admin/aduan/export/pdf`
- CRUD: `news-posts`, `leaders`, `documents`, `gallery-items`, `testimonials`
- `GET/PUT /admin/settings`

## Instalasi Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
npm install
npm run dev
php artisan serve
```

Akun admin default (dari seeder):

- Email: `admin@ppa.local`
- Password: `password`

Dapat diubah via env sebelum `db:seed`:

- `ADMIN_NAME`
- `ADMIN_EMAIL`
- `ADMIN_PASSWORD`

## Konfigurasi ENV Penting

Tambahkan di `.env` production:

```env
APP_NAME="PPA PPO"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

HOTLINE_WA_NUMBER=6281234567890

TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=...
TURNSTILE_SECRET_KEY=...
```

## Deploy ke cPanel Shared Hosting

### Opsi A (direkomendasikan)

Jika domain/subdomain bisa diarahkan ke folder `public` Laravel:

1. Upload project ke folder non-public (contoh: `/home/USER/ppa-app`)
2. Set document root domain ke `/home/USER/ppa-app/public`
3. Set `.env` production
4. Jalankan migrasi: `php artisan migrate --force`
5. Jalankan: `php artisan storage:link`
6. Build aset: `npm run build` (lokal), upload folder `public/build`

### Opsi B (jika domain utama terkunci di `public_html`)

1. Upload core Laravel ke `/home/USER/ppa-app`
2. Copy isi folder `/home/USER/ppa-app/public` ke `/home/USER/public_html`
3. Edit `public_html/index.php`:
   - arahkan ke `../ppa-app/vendor/autoload.php`
   - arahkan ke `../ppa-app/bootstrap/app.php`
4. Simpan `.env` di luar `public_html` (di folder app)
5. Jalankan migrasi + `storage:link` dari folder app

## Catatan Keamanan

- Field sensitif aduan (`nik`, `no_hp`, `email`) terenkripsi di database
- Daftar aduan di admin menampilkan data masked
- Form aduan dilindungi Turnstile + rate limit (`complaints` limiter)
- Route admin dibatasi middleware `auth` + `admin`

## Testing

```bash
php artisan test
```

Jika asset build dibutuhkan:

```bash
npm run build
```

Catatan: pada environment Node versi lama, `vite` dapat meminta Node versi lebih baru.
Konfigurasi saat ini sudah disesuaikan agar kompatibel dengan Node `18.x` (Vite `4.x`).

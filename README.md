## Setup

1. **Clone repo**
   ```bash
   git clone linkgithubsumber
   cd folderRepoYangDiClone
   ```

2. **Instal dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Buat file `.env`**
   ```bash
   cp .env.example .env
   ```

4. **Generate key**
   ```bash
   php artisan key:generate
   ```

5. **Buat database**
   - Buat database baru (nama bebas)
   - Edit file `.env` dan isi:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=namaDBkalian
     DB_USERNAME=root
     DB_PASSWORD=
     ```

6. **Migrasi dan seeder**
   ```bash
   php artisan migrate --seed
   ```

7. **Buat storage link (untuk akses file upload)**
   ```bash
   php artisan storage:link
   ```

8. **Jalankan server**
   ```bash
   php artisan serve
   ```

---

## Testing

Setelah `php artisan migrate --seed`, kamu bisa login sebagai **8 role berbeda** untuk testing:

### Dummy Users

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@admin.com` | `admin123` |
| Relawan | `relawan@test.com` | `password` |
| Kadus | `kadus@test.com` | `password` |
| Kabid | `kabid@test.com` | `password` |
| Desa | `desa@test.com` | `password` |
| Ketua Tim | `ketuatim@test.com` | `password` |
| Pegawai | `pegawai@test.com` | `password` |
| Petugas | `petugas@test.com` | `password` |

### Dashboard per Role

Setiap role melihat dashboard dengan data statistik berbeda:

- **Admin** — Total pengaduan, posko, gudang, bencana, warga terdampak
- **Relawan** — Pengaduan saya (total, pending, diproses)
- **Kadus** — Warga terdampak, pengaduan desa
- **Kabid** — Bencana aktif, posko, distribusi
- **Desa** — Warga terdampak, pengaduan desa
- **Ketua Tim** — Posko, dapur umum, distribusi pending
- **Pegawai** — Stok gudang, distribusi, barang masuk/keluar
- **Petugas** — Posko, dapur umum, warga, kebutuhan harian

---

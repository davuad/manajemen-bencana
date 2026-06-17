# System Map - Disaster Management (Manajemen Bencana) Application

This document provides a comprehensive technical overview and mapping of the disaster management application codebase.

---

## 1. System Overview

The application is a web-based **Disaster Management System (Manajemen Bencana)** designed to assist local government and emergency services in handling logistics, incident reporting (pengaduan), shelter management, victim tracking, and team coordinating.

- **Framework:** Laravel 13.x (PHP 8.3+)
- **Frontend:** Tailwind CSS, Blade Templates, compiled using Vite
- **Authentication & Authorization:** Laravel Breeze (Session-based) + Spatie Laravel Permission (Roles & Permissions)
- **Database:** MySQL / MariaDB (configured via `.env`)

---

## 2. Directory Structure

```
manajemen-bencana/
├── app/                        # Application Core Logic
│   ├── Http/
│   │   ├── Controllers/        # Business logic controllers (Logistics, Incidents, Shelters, Users)
│   │   ├── Middleware/         # Custom HTTP middleware
│   │   └── Requests/           # Validation classes for forms (e.g. Profile updates, authentication)
│   ├── Models/                 # Eloquent ORM Models mapping to database tables
│   └── Providers/              # Application Service Providers (AppServiceProvider, etc.)
├── bootstrap/                  # Framework bootstrapping config
│   ├── app.php                 # Middleware registration, routing setup, exception configuration
│   └── providers.php           # Service provider registration
├── config/                     # Core application configuration (app, auth, database, permission, etc.)
├── database/                   # Database schemas, migrations, seeders
│   ├── migrations/             # Table structures
│   └── seeders/                # Default seeders (RolePermissionSeeder, DatabaseSeeder)
├── public/                     # Compiled public assets (images, CSS, JS)
├── resources/                  # Uncompiled assets, views, and languages
│   ├── js/                     # JS assets (Alpine.js setup, bootstrap files)
│   ├── css/                    # Tailwind CSS definitions
│   └── views/                  # Blade templates (arranged by feature directory)
├── routes/                     # Application routes configuration
│   ├── web.php                 # Web interface routes (protected & public routes)
│   └── console.php             # Custom Artisan command registrations
├── storage/                    # Uploaded files, logs, and framework cache
├── vite.config.js              # Vite compiler configuration
├── tailwind.config.js          # Tailwind CSS layout settings
├── package.json                # Frontend package dependencies (npm)
└── composer.json               # Backend dependencies (Composer)
```

---

## 3. Database Schema Overview

The database structure spans several primary domains: users & roles, disaster incidents, logistics management, public reporting, and camp operations.

### Core Tables & Models

1. **`user` (`User.php`)**
    - Stores users (Admin, Relawan, Kabid, Kadus, Desa, Ketua Tim).
    - Columns: `id`, `nama`, `email`, `password`, `nik`, `no_wa`, `alamat`, `deskripsi`, `foto`, `status`, `remember_token`, timestamps.

2. **`bencana` (`Bencana.php`)**
    - Tracks disaster events.
    - Columns: `id`, `kategori_bencana_id`, `nama_bencana`, `lokasi`, `tanggal`, `status` (`aktif`/`selesai`), timestamps.
    - Relationships: Belongs to `KategoriBencana`.

3. **`kategori_bencana` (`KategoriBencana.php`)**
    - Defines types of disasters (e.g. Earthquake, Flood, Landslide).
    - Columns: `id`, `nama_kategori`, `deskripsi`, timestamps.

4. **`pengaduan_bencana` (`PengaduanBencana.php`)**
    - Public reporting system for disaster incidents.
    - Columns: `id`, `user_id`, `bencana_id`, `posko_id`, `nama_pelapor`, `lokasi_kejadian`, `deskripsi_kerusakan`, `kategori_kerusakan`, `status` (`pending`, `proses`, `selesai`), timestamps.
    - Relationships: Belongs to `User` (reporter), `Bencana`, and optionally `Posko`. Has many `FotoPengaduan` and `KebutuhanPengaduan`.

5. **`posko` (`Posko.php`)**
    - Coordinates evacuation points.
    - Columns: `id`, `nama_posko`, `lokasi`, `bencana_id`, `penanggung_jawab`, timestamps.
    - Relationships: Belongs to `Bencana`. Has many `DapurUmum`, `StokPosko`, `Pegawai`, and `Relawan`.

6. **`dapur_umum` (`DapurUmum.php`)**
    - Food stalls or kitchens at evacuation posts.
    - Columns: `id`, `posko_id`, `nama_dapur_umum`, `kapasitas`, `status` (`aktif`/`tidak aktif`), timestamps.

7. **`barang` (`Barang.php`)**
    - Catalog of logistics goods (e.g. rice, blankets, water).
    - Columns: `id`, `nama_barang`, `jenis_barang_id`, `deskripsi`, timestamps.
    - Relationships: Belongs to `JenisBarang`.

8. **`gudang` (`Gudang.php`)**
    - Storage centers holding logistics stock.
    - Columns: `id`, `nama_gudang`, `lokasi`, timestamps.

9. **`stok_gudang` (`StokGudang.php`)**
    - Quantities of items at specific warehouses.
    - Columns: `id`, `gudang_id`, `barang_id`, `stok`, timestamps.

10. **`barang_masuk` & `barang_keluar` (`BarangMasuk.php`, `BarangKeluar.php`)**
    - Tracks transactions of inventory entering or leaving warehouses.
    - Related tables: `detail_barang_masuk`, `detail_barang_keluar`.

11. **`distribusi` (`Distribusi.php`)**
    - Dispatch logistics records from posts or warehouses to affected areas.
    - Columns: `id`, `posko_id`, `tanggal_distribusi`, `keterangan`, timestamps.
    - Related tables: `detail_distribusi` (mapping items and amounts).

12. **`warga_terdampak` & `korban` (`WargaTerdampak.php`, `Korban.php`)**
    - Tracks residents impacted by the disaster and casualty records.
    - Columns for `korban`: `id`, `warga_terdampak_id`, `kondisi` (`luka ringan`, `luka berat`, `meninggal`, `hilang`), timestamps.

---

## 4. Routing and Authentication

### Authentication Flow

- Handled by standard Laravel Breeze components located in `app/Http/Controllers/Auth/`.
- Routes are registered in `routes/web.php` for `login`, `register`, `logout`, password resets, and email verification.

### Authorization (Spatie Laravel Permission)

Roles are managed dynamically. Default seed roles include:

- **`admin`**: Full application control (manajemen user, posko, role, laporan, barang, distribusi, pengaduan, etc.).
- **`relawan`**: Volunteer role restricted to viewing and reporting incidents (`lihat pengaduan`, `buat pengaduan`).
- **`kadus`** (Kepala Dusun), **`kabid`** (Kepala Bidang), **`desa`**, **`ketua_tim`**: Segmented administration access.

Middleware configurations mapped in `bootstrap/app.php`:

- `'role'` -> `Spatie\Permission\Middleware\RoleMiddleware`
- `'permission'` -> `Spatie\Permission\Middleware\PermissionMiddleware`
- `'role_or_permission'` -> `Spatie\Permission\Middleware\RoleOrPermissionMiddleware`

Routes in `routes/web.php` are wrapped in auth and role-based route groups:

```php
Route::middleware('auth')->group(function () {
    // Shared authenticated routes
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin dashboard, role management, user administration
});

Route::middleware(['auth', 'role:relawan'])->prefix('relawan')->name('relawan.')->group(function () {
    // Volunteer actions (incident reporting, volunteer profile)
});
```

---

## 5. Core Flows and Modules

```
                        ┌──────────────────┐
                        │      Public      │
                        └────────┬─────────┘
                                 │ Reports Incident (Pengaduan)
                                 ▼
                        ┌──────────────────┐
                        │ PengaduanBencana │
                        └────────┬─────────┘
                                 │ Reviewed by Admin / Team
                                 ▼
 ┌──────────────┐       ┌──────────────────┐       ┌──────────────┐
 │    Bencana   ├──────►│      Posko       │◄──────┤    Gudang    │
 └──────────────┘       └────────┬─────────┘       └──────┬───────┘
                                 │                        │
                    ┌────────────┴────────────┐           │ Sends Items
                    │                         │           ▼
                    ▼                         ▼    ┌──────────────┐
             ┌─────────────┐           ┌──────────┐│ BarangMasuk  │
             │ DapurUmum   │           │Relawan / │└──────────────┘
             └─────────────┘           │Pegawai   │
                                       └──────────┘
                                              │ Distributes Goods
                                              ▼
                                       ┌──────────────┐
                                       │  Distribusi  │
                                       └──────────────┘
```

### 1. Incident Reporting (Pengaduan) Flow

1. An authenticated resident or **Relawan** creates an incident report (`PengaduanBencanaController@store`).
2. Attachments like disaster photos are processed and stored in `FotoPengaduan`.
3. Admin changes status from `pending` to `proses` and links the report to a specific disaster (`Bencana`) and command center (`Posko`).
4. Once addressed, status is set to `selesai`.

### 2. Logistics & Warehousing Flow

1. Logistics catalog is managed under `BarangController` and `JenisBarangController`.
2. Goods arrive at warehouse (`GudangController`, `BarangMasukController`), which increments `StokGudang`.
3. Goods are sent out to posko evacuation posts (`BarangKeluarController`), reducing `StokGudang` and updating `StokPosko`.
4. Command centers issue and distribute packs of items to affected families via `DistribusiController` (using `DetailDistribusi` mapping).

### 3. Disaster & Posko Operations

1. Disasters are logged via `BencanaController`.
2. Locations for shelters are initialized under `PoskoController`.
3. Support elements (kitchen caps under `DapurUmumController` and personnel roster under `PetugasController`/`RelawanController`) are assigned to coordinate operations.

---

## 6. Frontend & Compiled Assets

- **Views Structure:** Located in `resources/views/`
    - `layouts/`: Core frames (App shell, navigation bars, guest landing layouts).
    - `gudang/`, `stok_gudang/`, `kategori_bantuan/`, `management_barang/`, `management_distribusi/`: Logistics, warehousing, and inventory templates.
    - `bencana/`, `kategori_bencana/`, `pengaduan_bencana/`: Disaster, category, and report screens.
    - `management_pegawai/`: Employee, volunteer roster screens.
    - `dashboard.blade.php`: Combined status panels for administrators.
- **Tailwind Integration:** Configured in `tailwind.config.js` and input styles in `resources/css/app.css`. Compiled alongside JS assets using Vite:
    - Command to compile: `npm run build` or development server: `npm run dev`

---

## 7. Configuration Details

- **PHP Version Requirement:** PHP >= 8.2 (indicated by Composer constraints).
- **Core Dependencies:**
    - `spatie/laravel-permission`: Manages system roles.
    - Laravel Breeze: Authentication scaffolding.
- **Environment variables (`.env`)**:
    - `DB_CONNECTION=mysql`
    - `DB_HOST=127.0.0.1`
    - `DB_PORT=3306`
    - `DB_DATABASE=manajemen_bencana`

---

## 8. Potential Risk & Future Expansion Areas

1. **Unused / Dead Code Models:**
    - Models `AnakTerpisah`, `Penjemput`, and `PenjemputanAnak` exist in `app/Models` but have no corresponding controller implementations or endpoints in `routes/web.php`. These are likely placeholder entities for child protection/family reunification modules.
2. **Missing Notification APIs:**
    - Although the `user` table includes a `no_wa` (WhatsApp number) column, there are currently no third-party integrations (like Twilio, Fonnte, etc.) configured in controllers or services to automate messaging. Messages must currently be sent manually, or the system expects manual external communications.
3. **Logistics Transaction Integrity:**
    - Operations on warehouse inventory transactions (`BarangMasuk`, `BarangKeluar`) adjust `StokGudang` values. High concurrency environments may encounter stock race conditions without transactional lock guards (like DB transactions with `sharedLock` or `lockForUpdate`).

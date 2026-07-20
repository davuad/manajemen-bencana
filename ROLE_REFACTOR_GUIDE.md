# Role & Permission Refactor Guide

## Goal
Refactor sistem role & permission dari **hardcoded** (role-based middleware) menjadi **dinamis** (permission-based middleware + CRUD flags).

---

## Pendekatan: A (1 Permission = 1 Item + CRUD Flags)

- Setiap fitur = **1 permission** (bukan 4 terpisah)
- Setiap permission punya flag: **create, read, update, delete**
- Contoh: `"manajemen user"` → C ✅ R ✅ U ✅ D ✅

### Daftar Permission (9)

| # | Permission | Keterangan |
|---|-----------|------------|
| 1 | `manajemen user` | Kelola data pengguna |
| 2 | `manajemen posko` | Kelola posko & dapur umum |
| 3 | `manajemen role` | Kelola role & permission |
| 4 | `manajemen laporan` | Kelola laporan |
| 5 | `manajemen barang` | Kelola barang & gudang |
| 6 | `manajemen distribusi` | Kelola distribusi bantuan |
| 7 | `manajemen pengaduan` | Kelola pengaduan bencana |
| 8 | `lihat pengaduan` | Lihat data pengaduan |
| 9 | `buat pengaduan` | Buat pengaduan baru |

---

## Current State

| Aspek | Status |
|-------|--------|
| Permission | 9 flat (tanpa CRUD flags) |
| Middleware routes | `role:` hardcoded (22 instance) |
| Sidebar | `@role('admin')` / `@hasRole()` hardcoded |
| Dashboard | `@elserole()` chain hardcoded |
| Role dengan permission | Hanya admin (9) & relawan (2) |
| Role tanpa permission | kadus, kabid, desa, ketua_tim, pegawai, petugas |

---

## Target State

| Aspek | Status |
|-------|--------|
| Permission | 9 + CRUD flags (tabel pivot) |
| Middleware routes | `permission:` dinamis |
| Sidebar | `@can('permission name')` dinamis |
| Dashboard | permission-based view selector |
| Semua role | Punya permission yang sesuai |

---

## Phases

### Phase 1: Static UI ✅

- [x] Tambah route `management-role` di `routes/web.php`
- [x] Buat `RoleController.php` (data statis)
- [x] Buat `management_role/index.blade.php` (tabel role)
- [x] Buat `management_role/edit.blade.php` (checklist permission + CRUD)
- [x] Tambah menu "Manajemen Role" di sidebar

### Phase 2: Database & Model ✅

- [x] Tambah kolom CRUD di migration `role_has_permissions` (bukan tabel baru)
- [x] Seeder: populate CRUD flags untuk admin & relawan
- [x] Update `RoleController`: ambil data dari DB (bukan hardcoded)

### Phase 3: Dynamic CRUD ✅

- [x] Update `RoleController@edit`: form bisa di-submit
- [x] Update `RoleController@update`: simpan CRUD flags ke DB
- [x] Tambah `RoleController@store`: buat role baru
- [x] Tambah `RoleController@destroy`: hapus role (guard: admin tidak bisa dihapus)

### Phase 4: Refactor Middleware

#### 4.1: Refactor Routes (`routes/web.php`) — 22 instance

| # | Task | Dari | Ke | Status |
|---|------|------|----|--------|
| 4.1.1 | Mapping role → permission | — | — | ☐ |
| 4.1.2 | Ubah route admin | `role:admin` | `permission:manajemen user` | ☐ |
| 4.1.3 | Ubah route kabid | `role:kabid` | `permission:manajemen pengaduan` | ☐ |
| 4.1.4 | Ubah route relawan | `role:relawan` | `permission:buat pengaduan` | ☐ |
| 4.1.5 | Ubah route kadus | `role:kadus` | `permission:buat pengaduan` | ☐ |
| 4.1.6 | Ubah route desa | `role:desa` | `permission:buat pengaduan` | ☐ |
| 4.1.7 | Ubah route ketua_tim | `role:ketua_tim` | `permission:manajemen pengaduan` | ☐ |
| 4.1.8 | Ubah route pegawai | `role:pegawai` | `permission:manajemen distribusi` | ☐ |
| 4.1.9 | Ubah route petugas | `role:petugas` | `permission:manajemen distribusi` | ☐ |
| 4.1.10 | Ubah route shared (management_posko) | `role:admin\|petugas\|pegawai` | `permission:manajemen posko` | ☐ |

#### 4.2: Refactor Sidebar (`sidebar.blade.php`) — 8 blok

| # | Task | Dari | Ke | Status |
|---|------|------|----|--------|
| 4.2.1 | Mapping menu → permission | — | — | ☐ |
| 4.2.2 | Menu Manajemen User | `@role('admin')` | `@can('manajemen user')` | ☐ |
| 4.2.3 | Menu Manajemen Role | `@role('admin')` | `@can('manajemen role')` | ☐ |
| 4.2.4 | Menu Pengaduan (admin) | `@hasRole('admin')` | `@can('manajemen pengaduan')` | ☐ |
| 4.2.5 | Menu Pengaduan (kabid) | `@hasRole('kabid')` | `@can('manajemen pengaduan')` | ☐ |
| 4.2.6 | Menu Pengaduan (relawan/kadus/desa) | `@hasRole('relawan')` | `@can('buat pengaduan')` | ☐ |
| 4.2.7 | Dropdown multi-role (Posko, Korban, Distribusi) | 7+ blok `@elseif` | `@can()` | ☐ |
| 4.2.8 | Menu tanpa guard (Gudang, Bencana, dll) | hardcoded | `@can('manajemen barang')` dll | ☐ |

#### 4.3: Refactor Dashboard (`dashboard.blade.php`) — 9 blok

| # | Task | Dari | Ke | Status |
|---|------|------|----|--------|
| 4.3.1 | Mapping dashboard → permission | — | — | ☐ |
| 4.3.2 | Dashboard admin | `@role('admin')` | `@can('manajemen user')` | ☐ |
| 4.3.3 | Dashboard relawan | `@elserole('relawan')` | `@can('buat pengaduan')` | ☐ |
| 4.3.4 | Dashboard kabid | `@elserole('kabid')` | `@can('manajemen pengaduan')` | ☐ |
| 4.3.5 | Dashboard kadus | `@elserole('kadus')` | `@can('buat pengaduan')` | ☐ |
| 4.3.6 | Dashboard desa | `@elserole('desa')` | `@can('buat pengaduan')` | ☐ |
| 4.3.7 | Dashboard ketua_tim | `@elserole('ketua_tim')` | `@can('manajemen pengaduan')` | ☐ |
| 4.3.8 | Dashboard pegawai | `@elserole('pegawai')` | `@can('manajemen distribusi')` | ☐ |
| 4.3.9 | Dashboard petugas | `@elserole('petugas')` | `@can('manajemen distribusi')` | ☐ |

#### 4.4: Testing & Cleanup — 5 task

| # | Task | Status |
|---|------|--------|
| 4.4.1 | Test login admin → cek semua menu & route | ☐ |
| 4.4.2 | Test login relawan → cek hanya menu yang sesuai | ☐ |
| 4.4.3 | Test login role lain → cek akses sesuai permission | ☐ |
| 4.4.4 | Test buat role baru via UI → cek bisa login & akses | ☐ |
| 4.4.5 | Hapus hardcoded data dari controller | ☐ |

### Phase 5: Testing & Cleanup

- [ ] Test setiap role bisa akses menu yang sesuai
- [ ] Test role baru bisa login & akses
- [ ] Hapus data hardcoded dari controller

---

## File Inventory

### Sudah Dibuat (Phase 1-3)

| File | Keterangan |
|------|------------|
| `app/Http/Controllers/RoleController.php` | Controller CRUD (index, create, store, edit, update, destroy) |
| `resources/views/management_role/index.blade.php` | Tabel role + jumlah permission |
| `resources/views/management_role/edit.blade.php` | Checklist permission + CRUD flags |
| `resources/views/management_role/create.blade.php` | Form tambah role baru |
| `routes/web.php` | Route `admin.management_role.*` |
| `resources/views/layouts/sidebar.blade.php` | Menu "Manajemen Role" |
| `database/migrations/2026_04_08_045301_create_permission_tables.php` | +4 kolom CRUD di pivot |
| `database/seeders/RolePermissionSeeder.php` | +CRUD flags admin & relawan |

### Akan Dimodifikasi (Phase 4)

| File | Keterangan |
|------|------------|
| `routes/web.php` | Ubah `role:` → `permission:` (22 instance) |
| `resources/views/layouts/sidebar.blade.php` | Ubah `@role()` → `@can()` |
| `resources/views/dashboard.blade.php` | Ubah `@elserole()` → `@can()` |

---

## Checklist Per Phase

### Phase 2 Checklist ✅
- [x] Migration: tambah kolom CRUD di `role_has_permissions`
- [x] Seeder: assign CRUD flags ke admin & relawan
- [x] Controller: query dari DB, bukan array statis

### Phase 3 Checklist ✅
- [x] Form edit: checkbox CRUD bisa dicentang/unchecked
- [x] Submit: simpan perubahan ke tabel pivot
- [x] Create: form input nama role baru
- [x] Delete: konfirmasi + guard admin

### Phase 4 Checklist
- [ ] Routes: 22 instance `role:` diubah ke `permission:` (4.1.1-4.1.10)
- [ ] Sidebar: semua `@role()` dan `@hasRole()` diubah ke `@can()` (4.2.1-4.2.8)
- [ ] Dashboard: `@elserole()` chain diubah ke `@can()` (4.3.1-4.3.9)
- [ ] Testing: semua role bisa akses sesuai permission (4.4.1-4.4.5)

### Phase 5 Checklist
- [ ] Login sebagai setiap role → cek sidebar
- [ ] Login sebagai setiap role → cek akses route
- [ ] Buat role baru via UI → cek bisa login & akses
- [ ] Hapus hardcoded data dari controller

---

## Catatan

- **Admin role TIDAK BOLEH dihapus** (guard di controller)
- **Permission `manajemen role`** hanya untuk admin
- **CRUD flags default:** `false` (kecuali admin yang dapat semua)
- **Sidebar & dashboard** refactor di Phase 4 (setelah DB & CRUD siap)

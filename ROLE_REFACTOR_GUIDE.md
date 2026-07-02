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

### Phase 2: Database & Model

- [ ] Buat migration: `role_permission_crud` (pivot: `role_id`, `permission_id`, `create`, `read`, `update`, `delete`)
- [ ] Seeder: populate CRUD flags untuk semua role existing
- [ ] Update `RoleController`: ambil data dari DB (bukan hardcoded)

### Phase 3: Dynamic CRUD

- [ ] Update `RoleController@edit`: form bisa di-submit
- [ ] Update `RoleController@update`: simpan CRUD flags ke DB
- [ ] Tambah `RoleController@store`: buat role baru
- [ ] Tambah `RoleController@destroy`: hapus role (guard: admin tidak bisa dihapus)

### Phase 4: Refactor Middleware

- [ ] Ubah `role:` → `permission:` di `routes/web.php` (22 instance)
- [ ] Refactor sidebar: `@role()` → `@can()`
- [ ] Refactor dashboard: `@elserole()` → `@can()`

### Phase 5: Testing & Cleanup

- [ ] Test setiap role bisa akses menu yang sesuai
- [ ] Test role baru bisa login & akses
- [ ] Hapus data hardcoded dari controller

---

## File Inventory

### Sudah Dibuat (Phase 1)

| File | Keterangan |
|------|------------|
| `app/Http/Controllers/RoleController.php` | Controller statis |
| `resources/views/management_role/index.blade.php` | Tabel role |
| `resources/views/management_role/edit.blade.php` | Detail permission + CRUD |
| `routes/web.php` | Route `admin.management_role.*` |
| `resources/views/layouts/sidebar.blade.php` | Menu "Manajemen Role" |

### Akan Dibuat (Phase 2-5)

| File | Keterangan |
|------|------------|
| `database/migrations/xxxx_create_role_permission_crud_table.php` | Tabel pivot CRUD flags |
| `database/seeders/RolePermissionCrudSeeder.php` | Data awal CRUD flags |

### Akan Dimodifikasi (Phase 4-5)

| File | Keterangan |
|------|------------|
| `routes/web.php` | Ubah `role:` → `permission:` |
| `resources/views/layouts/sidebar.blade.php` | Ubah `@role()` → `@can()` |
| `resources/views/dashboard.blade.php` | Ubah `@elserole()` → `@can()` |

---

## Checklist Per Phase

### Phase 2 Checklist
- [ ] Migration: tabel `role_permission_crud` dengan kolom `role_id`, `permission_id`, `create`, `read`, `update`, `delete`
- [ ] Seeder: assign CRUD flags ke setiap role
- [ ] Controller: query dari DB, bukan array statis

### Phase 3 Checklist
- [ ] Form edit: checkbox CRUD bisa dicentang/unchecked
- [ ] Submit: simpan perubahan ke tabel pivot
- [ ] Create: form input nama role baru
- [ ] Delete: konfirmasi + guard admin

### Phase 4 Checklist
- [ ] Routes: 22 instance `role:` diubah ke `permission:`
- [ ] Sidebar: semua `@role()` dan `@hasRole()` diubah ke `@can()`
- [ ] Dashboard: `@elserole()` chain diubah ke `@can()`

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

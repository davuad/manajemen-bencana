Berikut penjelasan **to the point** sesuai permintaanmu:

---

### 1. **Komposisi Nama File Migrasi**

Contoh: `2026_04_03_160136_create_pengaduan_bencana_table.php`

- **`2026_04_03_160136`** → Timestamp (urutan eksekusi migrasi).
- **`create_pengaduan_bencana_table`** → Nama tabel yang dibuat: `pengaduan_bencana`.
- **`.php`** → File migrasi Laravel.

---

### 2. **Pentingnya Urutan Migrasi**

> Laravel menjalankan migrasi **berdasarkan timestamp** — bukan berdasarkan nama tabel atau urutan file di folder.  
> Jika tabel A punya foreign key ke tabel B, maka **migrasi tabel B HARUS dijalankan lebih dulu**.  
> → Jika tidak urut, `php artisan migrate` akan **error** karena tabel referensi belum ada.

> 💥 Kasus kalian: Tiap mahasiswa bikin migrasi sendiri, tapi ga urut → error saat migrate.  
> **Solusi**: Pastikan migrasi tabel “induk” (tanpa foreign key) dibuat lebih dulu, baru tabel “anak”.

---

### 3. **Langkah Bikin File Migrasi**

```bash
# 1. Buat migrasi untuk tabel baru
php artisan make:migration create_nama_tabel_table --create=nama_tabel

# 2. Atau migrasi untuk tambah kolom ke tabel yang sudah ada
php artisan make:migration add_kolom_to_nama_tabel_table --table=nama_tabel

# 3. Edit file migrasi di database/migrations/ → isi schema di up() dan down()

# 4. Pastikan timestamp-nya sesuai urutan logika ERD (jika perlu, rename file manual)

# 5. Jalankan
php artisan migrate
```

---

✅ **Kesimpulan**:  
Urutkan migrasi sesuai ERD — mulai dari tabel tanpa foreign key → ke tabel yang bergantung.  
Jangan biarkan timestamp acak, apalagi kalau kerja kelompok!

Butuh bantu urutkan migrasi berdasarkan ERD kalian? Bisa kasih struktur tabelnya, saya bantu susun urutannya.

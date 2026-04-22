## Perubahan yang Dilakukan

- Fitur generate akun Admin Otomatis
- Fitur Register dan Login
- Fitur Show, Edit, Delete Data User (admin hanya bisa show)
- Halaman Profil dari tiap akun yang login
- Penambahan field Deskripsi pada tabel User
- Update Profil (beberapa field) tiap user yang login
- Perbaikan beberapa bug, seperti bug pada seeder

**Terakhir diupdate**: 11 April 2026, 11:00 WIB  
---
---
### 📦 Cara Menggunakan

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

7. **Login sebagai admin**  
   - Email: `admin@admin.com`  
   - Password: `admin123`  
   *(Atau buat akun baru via form register)*

8. **Jalankan server**  
   ```bash
   php artisan serve
   ```

---





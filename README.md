## Perubahan yang Dilakukan

perubahan path view untuk dashboard berdasarkan role, dari:

````blade
resources/views/components/
├── dashboard/
│   ├── admin.blade.php
│   ├── relawan.blade.php
│   ├── kadus.blade.php
│   ├── kabid.blade.php
│   ├── desa.blade.php
│   └── ketua_tim.blade.php

**Terakhir diupdate**: 24 April 2026, 11:00 WIB
---
---
### 📦 Cara Menggunakan

1. **Clone repo**
   ```bash
   git clone linkgithubsumber
   cd folderRepoYangDiClone
````

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
      _(Atau buat akun baru via form register)_

8. **Jalankan server**
    ```bash
    php artisan serve
    ```

---

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions = [
            'manajemen user',
            'manajemen posko',
            'manajemen role',
            'manajemen laporan',
            'manajemen barang',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /**
         * ROLES
         */

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = User::create([
            'nama' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'nik' => '1234567890123456',
            'no_wa' => '081234567890',
            'alamat' => 'Jl. Contoh Alamat No. 123, Kota Contoh',
        ]);
        $user->assignRole($admin);

        $relawan = Role::firstOrCreate(['name' => 'relawan']);
        $kadus = Role::firstOrCreate(['name' => 'kadus']);
        $kabid = Role::firstOrCreate(['name' => 'kabid']);
        $desa = Role::firstOrCreate(['name' => 'desa']);
        $ketua_tim = Role::firstOrCreate(['name' => 'ketua_tim']);

        $admin->givePermissionTo(Permission::all());
        $relawan->syncPermissions(['lihat pengaduan', 'buat pengaduan']);
    }
}

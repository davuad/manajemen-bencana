<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'manajemen user',
            'manajemen posko',
            'manajemen role',
            'manajemen laporan',
            'manajemen barang',
            'manajemen distribusi',
            'manajemen pengaduan',
            'lihat pengaduan',
            'buat pengaduan',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName]);
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $relawan = Role::firstOrCreate(['name' => 'relawan']);
        $kadus = Role::firstOrCreate(['name' => 'kadus']);
        $kabid = Role::firstOrCreate(['name' => 'kabid']);
        $desa = Role::firstOrCreate(['name' => 'desa']);
        $ketua_tim = Role::firstOrCreate(['name' => 'ketua_tim']);
        $pegawai = Role::firstOrCreate(['name' => 'pegawai']);
        $petugas = Role::firstOrCreate(['name' => 'petugas']);

        // Assign permissions to admin (all CRUD)
        $admin->givePermissionTo(Permission::all());
        foreach (Permission::all() as $perm) {
            $admin->permissions()->updateExistingPivot($perm->id, [
                'create' => true,
                'read' => true,
                'update' => true,
                'delete' => true,
            ]);
        }

        // Assign permissions to relawan
        $relawan->syncPermissions(['lihat pengaduan', 'buat pengaduan']);
        $relawan->permissions()->updateExistingPivot(
            Permission::where('name', 'lihat pengaduan')->first()->id,
            ['create' => false, 'read' => true, 'update' => false, 'delete' => false]
        );
        $relawan->permissions()->updateExistingPivot(
            Permission::where('name', 'buat pengaduan')->first()->id,
            ['create' => true, 'read' => true, 'update' => false, 'delete' => false]
        );

        // Create admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'nama' => 'Admin',
                'foto' => 'users/foto-admin.jpg',
                'deskripsi' => 'Administrator Sistem',
                'password' => bcrypt('admin123'),
                'nik' => '1234567890123456',
                'no_wa' => '081234567890',
                'alamat' => 'Jl. Contoh Alamat No. 123, Kota Contoh',
            ]
        );
        $user->assignRole($admin);
    }
}

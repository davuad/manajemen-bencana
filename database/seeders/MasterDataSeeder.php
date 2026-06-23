<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Bencana;
use App\Models\Desa;
use App\Models\Gudang;
use App\Models\JenisBarang;
use App\Models\KategoriBantuan;
use App\Models\KategoriBencana;
use App\Models\SumberBarangMasuk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedDesa();
        $this->seedKategoriBencana();
        $this->seedSumberBarangMasuk();
        $this->seedJenisBarang();
        $this->seedGudang();
        $this->seedBarang();
        $this->seedKategoriBantuan();
        $this->seedBencana();
        $this->seedUsers();
    }

    protected function seedDesa(): void
    {
        $data = [
            ['nama_desa' => 'Desa Sukamaju', 'kecamatan' => 'Cikampek', 'nama_kades' => 'Asep Saepuloh', 'kontak_kades' => '081234567891'],
            ['nama_desa' => 'Desa Mekarsari', 'kecamatan' => 'Telukjambe', 'nama_kades' => 'Siti Nurhaliza', 'kontak_kades' => '081234567892'],
            ['nama_desa' => 'Desa Kertaraharja', 'kecamatan' => 'Pedes', 'nama_kades' => 'Dadang Kurniawan', 'kontak_kades' => '081234567893'],
        ];

        foreach ($data as $item) {
            Desa::firstOrCreate(['nama_desa' => $item['nama_desa']], $item);
        }
    }

    protected function seedKategoriBencana(): void
    {
        $data = [
            ['nama_kategori' => 'Banjir', 'deskripsi' => 'Banjir bandang dan genangan air akibat curah hujan tinggi'],
            ['nama_kategori' => 'Gempa Bumi', 'deskripsi' => 'Gempa bumi tektonik dan vulkanik'],
            ['nama_kategori' => 'Tanah Longsor', 'deskripsi' => 'Longsor akibat hujan deras dan kerusakan lingkungan'],
        ];

        foreach ($data as $item) {
            KategoriBencana::firstOrCreate(['nama_kategori' => $item['nama_kategori']], $item);
        }
    }

    protected function seedSumberBarangMasuk(): void
    {
        $data = [
            ['id_sumber' => 'S01', 'nama_sumber' => 'Pemerintah Pusat', 'keterangan' => 'Bantuan dari pemerintah pusat melalui BNPB'],
            ['id_sumber' => 'S02', 'nama_sumber' => 'Donasi Masyarakat', 'keterangan' => 'Donasi dari masyarakat umum'],
            ['id_sumber' => 'S03', 'nama_sumber' => 'Lembaga Swadaya', 'keterangan' => 'Bantuan dari organisasi non-pemerintah'],
        ];

        foreach ($data as $item) {
            SumberBarangMasuk::firstOrCreate(['id_sumber' => $item['id_sumber']], $item);
        }
    }

    protected function seedJenisBarang(): void
    {
        $data = [
            ['id_jenis_barang' => 'JB01', 'nama_jenis_barang' => 'Bahan Pokok', 'keterangan' => 'Kebutuhan pangan pokok masyarakat'],
            ['id_jenis_barang' => 'JB02', 'nama_jenis_barang' => 'Alat Kesehatan', 'keterangan' => 'Perlengkapan medis dan kesehatan'],
            ['id_jenis_barang' => 'JB03', 'nama_jenis_barang' => 'Perlengkapan', 'keterangan' => 'Perlengkapan umum dan sandang'],
        ];

        foreach ($data as $item) {
            JenisBarang::firstOrCreate(['id_jenis_barang' => $item['id_jenis_barang']], $item);
        }
    }

    protected function seedGudang(): void
    {
        $data = [
            ['nama_gudang' => 'Gudang Pusat', 'alamat' => 'Jl. Raya Nasional No.1', 'kapasitas' => 1000, 'keterangan' => 'Gudang utama penyimpanan logistik'],
            ['nama_gudang' => 'Gudang Cabang', 'alamat' => 'Jl. Kecamatan No.10', 'kapasitas' => 500, 'keterangan' => 'Gudang cabang untuk distribusi lokal'],
            ['nama_gudang' => 'Gudang Lapangan', 'alamat' => 'Jl. Desa No.25', 'kapasitas' => 200, 'keterangan' => 'Gudang lapangan dekat lokasi bencana'],
        ];

        foreach ($data as $item) {
            Gudang::firstOrCreate(['nama_gudang' => $item['nama_gudang']], $item);
        }
    }

    protected function seedBarang(): void
    {
        $data = [
            ['id_barang' => 'BRG001', 'nama_barang' => 'Beras', 'id_jenis_barang' => 'JB01', 'stok' => 100, 'satuan' => 'Karung', 'keterangan' => 'Beras premium 5kg'],
            ['id_barang' => 'BRG002', 'nama_barang' => 'Masker', 'id_jenis_barang' => 'JB02', 'stok' => 500, 'satuan' => 'Box', 'keterangan' => 'Masker medis 3 ply'],
            ['id_barang' => 'BRG003', 'nama_barang' => 'Selimut', 'id_jenis_barang' => 'JB03', 'stok' => 200, 'satuan' => 'Pcs', 'keterangan' => 'Selimut wol tebal'],
        ];

        foreach ($data as $item) {
            Barang::firstOrCreate(['id_barang' => $item['id_barang']], $item);
        }
    }

    protected function seedKategoriBantuan(): void
    {
        $data = [
            ['id_sumber' => 'S01', 'nama_kategori' => 'Bantuan Sembako', 'keterangan' => 'Paket sembako untuk warga terdampak'],
            ['id_sumber' => 'S02', 'nama_kategori' => 'Bantuan Medis', 'keterangan' => 'Bantuan obat-obatan dan alat kesehatan'],
            ['id_sumber' => 'S03', 'nama_kategori' => 'Bantuan Logistik', 'keterangan' => 'Bantuan logistik dan perlengkapan lapangan'],
        ];

        foreach ($data as $item) {
            KategoriBantuan::firstOrCreate(
                ['nama_kategori' => $item['nama_kategori']],
                $item
            );
        }
    }

    protected function seedBencana(): void
    {
        $desaSukamaju = Desa::where('nama_desa', 'Desa Sukamaju')->first();
        $desaMekarsari = Desa::where('nama_desa', 'Desa Mekarsari')->first();
        $desaKertaraharja = Desa::where('nama_desa', 'Desa Kertaraharja')->first();

        $banjir = KategoriBencana::where('nama_kategori', 'Banjir')->first();
        $gempa = KategoriBencana::where('nama_kategori', 'Gempa Bumi')->first();
        $longsor = KategoriBencana::where('nama_kategori', 'Tanah Longsor')->first();

        $data = [
            [
                'nama_bencana' => 'Banjir Bandang Cikampek',
                'kategori_id' => $banjir->id,
                'desa_id' => $desaSukamaju->id,
                'tanggal' => '2026-01-15',
                'tingkat_kerusakan' => 'Berat',
                'status_bencana' => 'berlangsung',
            ],
            [
                'nama_bencana' => 'Gempa Bumi Cianjur',
                'kategori_id' => $gempa->id,
                'desa_id' => $desaMekarsari->id,
                'tanggal' => '2025-11-21',
                'tingkat_kerusakan' => 'Berat',
                'status_bencana' => 'selesai',
            ],
            [
                'nama_bencana' => 'Longsor Kertaraharja',
                'kategori_id' => $longsor->id,
                'desa_id' => $desaKertaraharja->id,
                'tanggal' => '2026-03-10',
                'tingkat_kerusakan' => 'Sedang',
                'status_bencana' => 'berlangsung',
            ],
        ];

        foreach ($data as $item) {
            Bencana::firstOrCreate(['nama_bencana' => $item['nama_bencana']], $item);
        }
    }

    protected function seedUsers(): void
    {
        $users = [
            ['nama' => 'Relawan Test', 'email' => 'relawan@test.com', 'role' => 'relawan'],
            ['nama' => 'Kadus Test', 'email' => 'kadus@test.com', 'role' => 'kadus'],
            ['nama' => 'Kabid Test', 'email' => 'kabid@test.com', 'role' => 'kabid'],
            ['nama' => 'Desa Test', 'email' => 'desa@test.com', 'role' => 'desa'],
            ['nama' => 'Ketua Tim Test', 'email' => 'ketuatim@test.com', 'role' => 'ketua_tim'],
            ['nama' => 'Pegawai Test', 'email' => 'pegawai@test.com', 'role' => 'pegawai'],
            ['nama' => 'Petugas Test', 'email' => 'petugas@test.com', 'role' => 'petugas'],
        ];

        foreach ($users as $item) {
            $user = User::firstOrCreate(
                ['email' => $item['email']],
                [
                    'nama' => $item['nama'],
                    'password' => Hash::make('password'),
                    'nik' => fake()->unique()->numerify('################'),
                    'no_wa' => fake()->numerify('08##########'),
                    'alamat' => fake()->address(),
                    'status' => 'aktif',
                ]
            );

            if (!$user->hasRole($item['role'])) {
                $user->assignRole($item['role']);
            }
        }
    }
}

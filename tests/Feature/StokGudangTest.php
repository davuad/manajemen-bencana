<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Gudang;
use App\Models\JenisBarang;
use App\Models\Barang;
use App\Models\StokGudang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StokGudangTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_non_admin_cannot_access_stok_gudang()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.stok_gudang.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_stok_gudang_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $gudang = Gudang::create([
            'nama_gudang' => 'Gudang Alpha',
            'alamat' => 'Alamat Alpha',
            'kapasitas' => 1000,
            'keterangan' => 'Ket Alpha',
        ]);

        $jenis = JenisBarang::create([
            'id_jenis_barang' => 'JNS01',
            'nama_jenis_barang' => 'Makanan',
            'keterangan' => 'Makanan instan',
        ]);

        $barang = Barang::create([
            'id_barang' => 'BRG01',
            'nama_barang' => 'Mie Instan',
            'id_jenis_barang' => $jenis->id_jenis_barang,
            'stok' => 100,
            'satuan' => 'Dus',
            'keterangan' => 'Mie goreng',
        ]);

        StokGudang::create([
            'gudang_id' => $gudang->id,
            'barang_id' => $barang->id_barang,
            'jumlah_stok' => 50,
            'kondisi_barang' => 'baik',
            'keterangan' => 'Stok aman',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.stok_gudang.index'));
        $response->assertStatus(200);
        $response->assertSee('Gudang Alpha');
        $response->assertSee('Mie Instan');
    }

    public function test_admin_can_create_stok_gudang()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $gudang = Gudang::create([
            'nama_gudang' => 'Gudang Beta',
            'alamat' => 'Alamat Beta',
            'kapasitas' => 1000,
            'keterangan' => 'Ket Beta',
        ]);

        $jenis = JenisBarang::create([
            'id_jenis_barang' => 'JNS02',
            'nama_jenis_barang' => 'Selimut',
            'keterangan' => 'Selimut hangat',
        ]);

        $barang = Barang::create([
            'id_barang' => 'BRG02',
            'nama_barang' => 'Selimut Wool',
            'id_jenis_barang' => $jenis->id_jenis_barang,
            'stok' => 100,
            'satuan' => 'Pcs',
            'keterangan' => 'Selimut tebal',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.stok_gudang.store'), [
            'gudang_id' => $gudang->id,
            'barang_id' => $barang->id_barang,
            'jumlah_stok' => 40,
            'kondisi_barang' => 'baik',
            'keterangan' => 'Barang baru datang',
        ]);

        $response->assertRedirect(route('admin.stok_gudang.index'));
        $this->assertDatabaseHas('stok_gudang', [
            'gudang_id' => $gudang->id,
            'barang_id' => $barang->id_barang,
            'jumlah_stok' => 40,
            'kondisi_barang' => 'baik',
            'keterangan' => 'Barang baru datang',
        ]);
    }

    public function test_admin_can_edit_stok_gudang()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $gudang = Gudang::create([
            'nama_gudang' => 'Gudang Gamma',
            'alamat' => 'Alamat Gamma',
            'kapasitas' => 1000,
            'keterangan' => 'Ket Gamma',
        ]);

        $jenis = JenisBarang::create([
            'id_jenis_barang' => 'JNS03',
            'nama_jenis_barang' => 'Tenda',
            'keterangan' => 'Tenda darurat',
        ]);

        $barang = Barang::create([
            'id_barang' => 'BRG03',
            'nama_barang' => 'Tenda Keluarga',
            'id_jenis_barang' => $jenis->id_jenis_barang,
            'stok' => 10,
            'satuan' => 'Pcs',
            'keterangan' => 'Tenda dome',
        ]);

        $stok = StokGudang::create([
            'gudang_id' => $gudang->id,
            'barang_id' => $barang->id_barang,
            'jumlah_stok' => 5,
            'kondisi_barang' => 'baik',
            'keterangan' => 'Awal',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.stok_gudang.update', $stok->id), [
            'gudang_id' => $gudang->id,
            'barang_id' => $barang->id_barang,
            'jumlah_stok' => 8,
            'kondisi_barang' => 'rusak',
            'keterangan' => 'Diperbarui',
        ]);

        $response->assertRedirect(route('admin.stok_gudang.index'));
        $this->assertDatabaseHas('stok_gudang', [
            'id' => $stok->id,
            'gudang_id' => $gudang->id,
            'barang_id' => $barang->id_barang,
            'jumlah_stok' => 8,
            'kondisi_barang' => 'rusak',
            'keterangan' => 'Diperbarui',
        ]);
    }

    public function test_admin_can_delete_stok_gudang()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $gudang = Gudang::create([
            'nama_gudang' => 'Gudang Delta',
            'alamat' => 'Alamat Delta',
            'kapasitas' => 1000,
            'keterangan' => 'Ket Delta',
        ]);

        $jenis = JenisBarang::create([
            'id_jenis_barang' => 'JNS04',
            'nama_jenis_barang' => 'Air',
            'keterangan' => 'Air minum',
        ]);

        $barang = Barang::create([
            'id_barang' => 'BRG04',
            'nama_barang' => 'Air Mineral',
            'id_jenis_barang' => $jenis->id_jenis_barang,
            'stok' => 200,
            'satuan' => 'Galon',
            'keterangan' => 'Air galon',
        ]);

        $stok = StokGudang::create([
            'gudang_id' => $gudang->id,
            'barang_id' => $barang->id_barang,
            'jumlah_stok' => 150,
            'kondisi_barang' => 'baik',
            'keterangan' => 'Siap kirim',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.stok_gudang.destroy', $stok->id));

        $response->assertRedirect(route('admin.stok_gudang.index'));
        $this->assertDatabaseMissing('stok_gudang', [
            'id' => $stok->id,
        ]);
    }
}

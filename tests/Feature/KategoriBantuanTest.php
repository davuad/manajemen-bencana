<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Sumber;
use App\Models\KategoriBantuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KategoriBantuanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_non_admin_cannot_access_kategori_bantuan()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.kategori_bantuan.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_kategori_bantuan_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $sumber = Sumber::create([
            'id_sumber' => 'SMB01',
            'nama_sumber' => 'APBD',
            'keterangan' => 'Anggaran Pendapatan Belanja Daerah',
        ]);

        KategoriBantuan::create([
            'id_sumber' => $sumber->id_sumber,
            'nama_kategori' => 'Makanan Pokok',
            'keterangan' => 'Bantuan berupa sembako',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.kategori_bantuan.index'));
        $response->assertStatus(200);
        $response->assertSee('Makanan Pokok');
    }

    public function test_admin_can_create_kategori_bantuan()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $sumber = Sumber::create([
            'id_sumber' => 'SMB02',
            'nama_sumber' => 'BNPB',
            'keterangan' => 'Badan Nasional Penanggulangan Bencana',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.kategori_bantuan.store'), [
            'id_sumber' => $sumber->id_sumber,
            'nama_kategori' => 'Peralatan Medis',
            'keterangan' => 'Obat-obatan dan alat kesehatan',
        ]);

        $response->assertRedirect(route('admin.kategori_bantuan.index'));
        $this->assertDatabaseHas('kategori_bantuan', [
            'id_sumber' => $sumber->id_sumber,
            'nama_kategori' => 'Peralatan Medis',
            'keterangan' => 'Obat-obatan dan alat kesehatan',
        ]);
    }

    public function test_admin_can_edit_kategori_bantuan()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $sumber = Sumber::create([
            'id_sumber' => 'SMB03',
            'nama_sumber' => 'Hibah',
            'keterangan' => 'Pemberian masyarakat/swasta',
        ]);

        $kategori = KategoriBantuan::create([
            'id_sumber' => $sumber->id_sumber,
            'nama_kategori' => 'Pakaian Layak Pakai',
            'keterangan' => 'Pakaian bayi dan dewasa',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.kategori_bantuan.update', $kategori->id), [
            'id_sumber' => $sumber->id_sumber,
            'nama_kategori' => 'Pakaian Layak Pakai Updated',
            'keterangan' => 'Pakaian bayi dan dewasa (termasuk selimut)',
        ]);

        $response->assertRedirect(route('admin.kategori_bantuan.index'));
        $this->assertDatabaseHas('kategori_bantuan', [
            'id' => $kategori->id,
            'nama_kategori' => 'Pakaian Layak Pakai Updated',
            'keterangan' => 'Pakaian bayi dan dewasa (termasuk selimut)',
        ]);
    }

    public function test_admin_can_delete_kategori_bantuan()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $sumber = Sumber::create([
            'id_sumber' => 'SMB04',
            'nama_sumber' => 'Donatur',
            'keterangan' => 'Sumbangan perorangan',
        ]);

        $kategori = KategoriBantuan::create([
            'id_sumber' => $sumber->id_sumber,
            'nama_kategori' => 'Tenda Darurat',
            'keterangan' => 'Tenda penampungan sementara',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.kategori_bantuan.destroy', $kategori->id));

        $response->assertRedirect(route('admin.kategori_bantuan.index'));
        $this->assertDatabaseMissing('kategori_bantuan', [
            'id' => $kategori->id,
        ]);
    }
}

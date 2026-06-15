<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Desa;
use App\Models\Bencana;
use App\Models\KategoriBencana;
use App\Models\Posko;
use App\Models\Korban;
use App\Models\PengaduanBencana;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KorbanTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $desa;
    protected $bencana;
    protected $posko;
    protected $pengaduan;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the admin role
        Role::firstOrCreate(['name' => 'admin']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Setup base data
        $this->desa = Desa::create([
            'nama_desa' => 'Desa Makmur',
            'kecamatan' => 'Kecamatan Jaya',
            'nama_kades' => 'Budi Santoso',
            'kontak_kades' => '081234567890',
        ]);

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Banjir',
            'deskripsi' => 'Banjir bandang',
        ]);

        $this->bencana = Bencana::create([
            'nama_bencana' => 'Banjir bandang Jakarta',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'sedang',
            'desa_id' => $this->desa->id,
        ]);

        $user = User::factory()->create();
        $this->pengaduan = PengaduanBencana::create([
            'user_id' => $user->id,
            'kategori_id' => $kategori->id,
            'desa' => 'Desa Makmur',
            'deskripsi' => 'Pengaduan tanggul jebol',
            'status_pengaduan' => 'PROSES',
        ]);

        $this->posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);
    }

    public function test_non_admin_cannot_access_korban()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.management_korban.korban.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_korban_index()
    {
        $korban = Korban::create([
            'bencana_id' => $this->bencana->id,
            'posko_id' => $this->posko->id,
            'nama' => 'Budi',
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'Laki-Laki',
            'umur' => 30,
            'alamat' => 'Desa Makmur RT 01',
            'lokasi_kejadian' => 'Dusun A',
            'tanggal_kejadian' => '2026-06-13',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_korban.korban.index'));
        $response->assertStatus(200);
        $response->assertSee('Budi');
    }

    public function test_admin_can_access_korban_create()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.management_korban.korban.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_korban()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.management_korban.korban.store'), [
            'bencana_id' => $this->bencana->id,
            'posko_id' => $this->posko->id,
            'nama' => 'Siti',
            'nik' => '1234567890123457',
            'jenis_kelamin' => 'Perempuan',
            'umur' => 25,
            'alamat' => 'Desa Makmur RT 02',
            'lokasi_kejadian' => 'Dusun B',
            'tanggal_kejadian' => '2026-06-13',
        ]);

        $response->assertRedirect(route('admin.management_korban.korban.index'));
        $this->assertDatabaseHas('korban', [
            'nama' => 'Siti',
            'nik' => '1234567890123457',
            'umur' => 25,
        ]);
    }

    public function test_admin_can_access_korban_show()
    {
        $korban = Korban::create([
            'bencana_id' => $this->bencana->id,
            'posko_id' => $this->posko->id,
            'nama' => 'Budi',
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'Laki-Laki',
            'umur' => 30,
            'alamat' => 'Desa Makmur RT 01',
            'lokasi_kejadian' => 'Dusun A',
            'tanggal_kejadian' => '2026-06-13',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_korban.korban.show', $korban->id));
        $response->assertStatus(200);
        $response->assertSee('Budi');
        $response->assertSee('1234567890123456');
    }

    public function test_admin_can_access_korban_edit()
    {
        $korban = Korban::create([
            'bencana_id' => $this->bencana->id,
            'posko_id' => $this->posko->id,
            'nama' => 'Budi',
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'Laki-Laki',
            'umur' => 30,
            'alamat' => 'Desa Makmur RT 01',
            'lokasi_kejadian' => 'Dusun A',
            'tanggal_kejadian' => '2026-06-13',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_korban.korban.edit', $korban->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_korban()
    {
        $korban = Korban::create([
            'bencana_id' => $this->bencana->id,
            'posko_id' => $this->posko->id,
            'nama' => 'Budi',
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'Laki-Laki',
            'umur' => 30,
            'alamat' => 'Desa Makmur RT 01',
            'lokasi_kejadian' => 'Dusun A',
            'tanggal_kejadian' => '2026-06-13',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.management_korban.korban.update', $korban->id), [
            'bencana_id' => $this->bencana->id,
            'posko_id' => $this->posko->id,
            'nama' => 'Budi Updated',
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'Laki-Laki',
            'umur' => 31,
            'alamat' => 'Desa Makmur RT 01 Updated',
            'lokasi_kejadian' => 'Dusun A Updated',
            'tanggal_kejadian' => '2026-06-14',
        ]);

        $response->assertRedirect(route('admin.management_korban.korban.index'));
        $this->assertDatabaseHas('korban', [
            'id' => $korban->id,
            'nama' => 'Budi Updated',
            'umur' => 31,
        ]);
    }

    public function test_admin_can_delete_korban()
    {
        $korban = Korban::create([
            'bencana_id' => $this->bencana->id,
            'posko_id' => $this->posko->id,
            'nama' => 'Budi',
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'Laki-Laki',
            'umur' => 30,
            'alamat' => 'Desa Makmur RT 01',
            'lokasi_kejadian' => 'Dusun A',
            'tanggal_kejadian' => '2026-06-13',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.management_korban.korban.destroy', $korban->id));

        $response->assertRedirect(route('admin.management_korban.korban.index'));
        $this->assertDatabaseMissing('korban', [
            'id' => $korban->id,
        ]);
    }

    public function test_admin_can_download_korban_pdf()
    {
        $korban = Korban::create([
            'bencana_id' => $this->bencana->id,
            'posko_id' => $this->posko->id,
            'nama' => 'Budi',
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'Laki-Laki',
            'umur' => 30,
            'alamat' => 'Desa Makmur RT 01',
            'lokasi_kejadian' => 'Dusun A',
            'tanggal_kejadian' => '2026-06-13',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_korban.korban.reviewPdf'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}

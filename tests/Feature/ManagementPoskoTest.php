<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Desa;
use App\Models\Bencana;
use App\Models\KategoriBencana;
use App\Models\PengaduanBencana;
use App\Models\Posko;
use App\Models\DapurUmum;
use App\Models\KebutuhanHarian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManagementPoskoTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $desa;
    protected $bencana;
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
        ]);

        $user = User::factory()->create();
        $this->pengaduan = PengaduanBencana::create([
            'user_id' => $user->id,
            'kategori_id' => $kategori->id,
            'desa' => 'Desa Makmur',
            'deskripsi' => 'Pengaduan tanggul jebol',
            'status_pengaduan' => 'PROSES',
        ]);
    }

    // --- POSKO TESTS ---

    public function test_non_admin_cannot_access_posko()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.management_posko.posko.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_posko_index()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.posko.index'));
        $response->assertStatus(200);
        $response->assertSee('Posko Utama');
    }

    public function test_admin_can_access_posko_create()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.posko.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_posko()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.management_posko.posko.store'), [
            'nama_posko' => 'Posko 2',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Masjid Al-Ikhlas',
        ]);

        $response->assertRedirect(route('admin.management_posko.posko.index'));
        $this->assertDatabaseHas('posko', [
            'nama_posko' => 'Posko 2',
            'lokasi' => 'Masjid Al-Ikhlas',
            'status' => 'aktif',
        ]);
    }

    public function test_admin_can_access_posko_edit()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.posko.edit', $posko->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_posko()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.management_posko.posko.update', $posko->id), [
            'nama_posko' => 'Posko Utama Updated',
            'tanggal_dibuat' => '2026-06-14',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Kantor Kecamatan',
            'status' => 'tidak aktif',
        ]);

        $response->assertRedirect(route('admin.management_posko.posko.index'));
        $this->assertDatabaseHas('posko', [
            'id' => $posko->id,
            'nama_posko' => 'Posko Utama Updated',
            'lokasi' => 'Kantor Kecamatan',
            'status' => 'tidak aktif',
        ]);
    }

    public function test_admin_can_delete_posko()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.management_posko.posko.destroy', $posko->id));
        $response->assertRedirect(route('admin.management_posko.posko.index'));
        $this->assertDatabaseMissing('posko', [
            'id' => $posko->id,
        ]);
    }

    // --- DAPUR UMUM TESTS ---

    public function test_non_admin_cannot_access_dapur_umum()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.management_posko.dapur_umum.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dapur_umum_index()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.dapur_umum.index'));
        $response->assertStatus(200);
        $response->assertSee('Dapur Utama');
    }

    public function test_admin_can_access_dapur_umum_create()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.dapur_umum.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_dapur_umum()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.management_posko.dapur_umum.store'), [
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur 2',
            'kapasitas_warga' => 300,
            'jumlah_warga' => 100,
            'penanggung_jawab' => 'Anto',
        ]);

        $response->assertRedirect(route('admin.management_posko.dapur_umum.index'));
        $this->assertDatabaseHas('dapur_umum', [
            'nama_dapur_umum' => 'Dapur 2',
            'penanggung_jawab' => 'Anto',
        ]);
    }

    public function test_admin_can_access_dapur_umum_edit()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.dapur_umum.edit', $dapur->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_dapur_umum()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.management_posko.dapur_umum.update', $dapur->id), [
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama Updated',
            'kapasitas_warga' => 600,
            'jumlah_warga' => 200,
            'penanggung_jawab' => 'Slamet Wijaya',
        ]);

        $response->assertRedirect(route('admin.management_posko.dapur_umum.index'));
        $this->assertDatabaseHas('dapur_umum', [
            'id' => $dapur->id,
            'nama_dapur_umum' => 'Dapur Utama Updated',
            'penanggung_jawab' => 'Slamet Wijaya',
        ]);
    }

    public function test_admin_can_delete_dapur_umum()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.management_posko.dapur_umum.destroy', $dapur->id));
        $response->assertRedirect(route('admin.management_posko.dapur_umum.index'));
        $this->assertDatabaseMissing('dapur_umum', [
            'id' => $dapur->id,
        ]);
    }

    // --- KEBUTUHAN HARIAN TESTS ---

    public function test_non_admin_cannot_access_kebutuhan_harian()
    {
        $user = User::factory()->create();

        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $response = $this->actingAs($user)->get(route('admin.management_posko.kebutuhan_harian.index', $dapur->id));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_kebutuhan_harian_index()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $kebutuhan = KebutuhanHarian::create([
            'dapur_umum_id' => $dapur->id,
            'tanggal' => '2026-06-13',
            'jumlah_warga' => 100,
            'porsi_per_orang' => 3,
            'total_porsi' => 300,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.kebutuhan_harian.index', $dapur->id));
        $response->assertStatus(200);
        $response->assertSee('2026-06-13');
    }

    public function test_admin_can_access_kebutuhan_harian_create()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.kebutuhan_harian.create', $dapur->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_kebutuhan_harian()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.management_posko.kebutuhan_harian.store', $dapur->id), [
            'tanggal' => '2026-06-13',
            'jumlah_warga' => 150,
            'porsi_per_orang' => 2,
        ]);

        $response->assertRedirect(route('admin.management_posko.kebutuhan_harian.index', $dapur->id));
        $this->assertDatabaseHas('kebutuhan_harian', [
            'dapur_umum_id' => $dapur->id,
            'tanggal' => '2026-06-13',
            'jumlah_warga' => 150,
            'porsi_per_orang' => 2,
            'total_porsi' => 300,
        ]);
    }

    public function test_admin_can_access_kebutuhan_harian_edit()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $kebutuhan = KebutuhanHarian::create([
            'dapur_umum_id' => $dapur->id,
            'tanggal' => '2026-06-13',
            'jumlah_warga' => 100,
            'porsi_per_orang' => 3,
            'total_porsi' => 300,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.management_posko.kebutuhan_harian.edit', $kebutuhan->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_kebutuhan_harian()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $kebutuhan = KebutuhanHarian::create([
            'dapur_umum_id' => $dapur->id,
            'tanggal' => '2026-06-13',
            'jumlah_warga' => 100,
            'porsi_per_orang' => 3,
            'total_porsi' => 300,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.management_posko.kebutuhan_harian.update', $kebutuhan->id), [
            'tanggal' => '2026-06-14',
            'jumlah_warga' => 120,
            'porsi_per_orang' => 3,
        ]);

        $response->assertRedirect(route('admin.management_posko.kebutuhan_harian.index', $dapur->id));
        $this->assertDatabaseHas('kebutuhan_harian', [
            'id' => $kebutuhan->id,
            'tanggal' => '2026-06-14',
            'jumlah_warga' => 120,
            'porsi_per_orang' => 3,
            'total_porsi' => 360,
        ]);
    }

    public function test_admin_can_delete_kebutuhan_harian()
    {
        $posko = Posko::create([
            'nama_posko' => 'Posko Utama',
            'tanggal_dibuat' => '2026-06-13',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'pengaduan_bencana_id' => $this->pengaduan->id,
            'lokasi' => 'Balai Desa Makmur',
            'status' => 'aktif'
        ]);

        $dapur = DapurUmum::create([
            'posko_id' => $posko->id,
            'nama_dapur_umum' => 'Dapur Utama',
            'kapasitas_warga' => 500,
            'jumlah_warga' => 150,
            'penanggung_jawab' => 'Slamet'
        ]);

        $kebutuhan = KebutuhanHarian::create([
            'dapur_umum_id' => $dapur->id,
            'tanggal' => '2026-06-13',
            'jumlah_warga' => 100,
            'porsi_per_orang' => 3,
            'total_porsi' => 300,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.management_posko.kebutuhan_harian.destroy', $kebutuhan->id));
        $response->assertRedirect(route('admin.management_posko.kebutuhan_harian.index', $dapur->id));
        $this->assertDatabaseMissing('kebutuhan_harian', [
            'id' => $kebutuhan->id,
        ]);
    }
}

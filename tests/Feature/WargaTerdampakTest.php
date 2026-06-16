<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Desa;
use App\Models\Bencana;
use App\Models\KategoriBencana;
use App\Models\WargaTerdampak;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WargaTerdampakTest extends TestCase
{
    use RefreshDatabase;

    protected $desa;
    protected $bencana;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the admin role
        Role::firstOrCreate(['name' => 'admin']);

        // Set up dependencies
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
    }

    public function test_non_admin_cannot_access_warga()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.warga.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_warga_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        WargaTerdampak::create([
            'no_kk' => '3201234567890001',
            'nik_kepala_keluarga' => '3201234567890002',
            'nama_kepala_keluarga' => 'Warga A',
            'alamat' => 'RT 01 RW 02',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'jumlah_anggota' => 4,
            'tanggal_pendataan' => '2026-06-13',
            'jenis_bantuan' => 'Bantuan Saat Bencana',
            'status_penyaluran' => 'Belum diproses',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.warga.index'));
        $response->assertStatus(200);
        $response->assertSee('Warga A');
    }

    public function test_admin_can_create_warga()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.warga.store'), [
            'no_kk' => '1111111111111111',
            'nik_kepala_keluarga' => '2222222222222222',
            'nama_kepala_keluarga' => 'Warga Baru',
            'alamat' => 'Alamat Baru',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'jumlah_anggota' => 3,
            'tanggal_pendataan' => '2026-06-13',
            'jenis_bantuan' => 'Bantuan Pasca Bencana',
            'status_penyaluran' => 'Belum diproses',
        ]);

        $response->assertRedirect(route('admin.warga.index'));
        $this->assertDatabaseHas('warga_terdampak', [
            'no_kk' => '1111111111111111',
            'nik_kepala_keluarga' => '2222222222222222',
            'nama_kepala_keluarga' => 'Warga Baru',
        ]);
    }

    public function test_admin_can_view_warga_detail()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $warga = WargaTerdampak::create([
            'no_kk' => '3201234567890003',
            'nik_kepala_keluarga' => '3201234567890004',
            'nama_kepala_keluarga' => 'Warga Detail',
            'alamat' => 'RT 03 RW 04',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'jumlah_anggota' => 5,
            'tanggal_pendataan' => '2026-06-13',
            'jenis_bantuan' => 'Bantuan Saat Bencana',
            'status_penyaluran' => 'Belum diproses',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.warga.detail', $warga->id));
        $response->assertStatus(200);
        $response->assertSee('Warga Detail');
    }

    public function test_admin_can_edit_warga()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $warga = WargaTerdampak::create([
            'no_kk' => '3201234567890005',
            'nik_kepala_keluarga' => '3201234567890006',
            'nama_kepala_keluarga' => 'Warga Edit',
            'alamat' => 'RT 05 RW 06',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'jumlah_anggota' => 2,
            'tanggal_pendataan' => '2026-06-13',
            'jenis_bantuan' => 'Bantuan Saat Bencana',
            'status_penyaluran' => 'Belum diproses',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.warga.update', $warga->id), [
            'no_kk' => '3201234567890005',
            'nik_kepala_keluarga' => '3201234567890006',
            'nama_kepala_keluarga' => 'Warga Edit Updated',
            'alamat' => 'RT 05 RW 06 Updated',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'jumlah_anggota' => 6,
            'tanggal_pendataan' => '2026-06-13',
            'jenis_bantuan' => 'Bantuan Pasca Bencana',
            'status_penyaluran' => 'Proses Penyaluran',
        ]);

        $response->assertRedirect(route('admin.warga.index'));
        $this->assertDatabaseHas('warga_terdampak', [
            'id' => $warga->id,
            'nama_kepala_keluarga' => 'Warga Edit Updated',
            'jumlah_anggota' => 6,
            'status_penyaluran' => 'Proses Penyaluran',
        ]);
    }

    public function test_admin_can_delete_warga()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $warga = WargaTerdampak::create([
            'no_kk' => '3201234567890007',
            'nik_kepala_keluarga' => '3201234567890008',
            'nama_kepala_keluarga' => 'Warga Hapus',
            'alamat' => 'RT 07 RW 08',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'jumlah_anggota' => 1,
            'tanggal_pendataan' => '2026-06-13',
            'jenis_bantuan' => 'Bantuan Saat Bencana',
            'status_penyaluran' => 'Belum diproses',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.warga.delete', $warga->id));

        $response->assertRedirect(route('admin.warga.index'));
        $this->assertDatabaseMissing('warga_terdampak', [
            'id' => $warga->id,
        ]);
    }

    public function test_admin_can_change_status_warga()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $warga = WargaTerdampak::create([
            'no_kk' => '3201234567890009',
            'nik_kepala_keluarga' => '3201234567890010',
            'nama_kepala_keluarga' => 'Warga Status',
            'alamat' => 'RT 09 RW 10',
            'desa_id' => $this->desa->id,
            'bencana_id' => $this->bencana->id,
            'jumlah_anggota' => 5,
            'tanggal_pendataan' => '2026-06-13',
            'jenis_bantuan' => 'Bantuan Saat Bencana',
            'status_penyaluran' => 'Belum diproses',
        ]);

        // Belum diproses -> Proses Penyaluran
        $response = $this->actingAs($admin)->post(route('admin.warga.ubahStatus', $warga->id));
        $response->assertRedirect(route('admin.warga.index'));
        $this->assertDatabaseHas('warga_terdampak', [
            'id' => $warga->id,
            'status_penyaluran' => 'Proses Penyaluran',
        ]);

        // Proses Penyaluran -> Sudah disalurkan
        $response = $this->actingAs($admin)->post(route('admin.warga.ubahStatus', $warga->id));
        $response->assertRedirect(route('admin.warga.index'));
        $this->assertDatabaseHas('warga_terdampak', [
            'id' => $warga->id,
            'status_penyaluran' => 'Sudah disalurkan',
        ]);
    }
}

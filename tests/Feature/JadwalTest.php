<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\KategoriBencana;
use App\Models\Bencana;
use App\Models\Pegawai;
use App\Models\JadwalLayanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JadwalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_non_admin_cannot_access_jadwal()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.jadwal.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_jadwal_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Banjir',
            'deskripsi' => 'Banjir bandang',
        ]);

        $bencana = Bencana::create([
            'nama_bencana' => 'Banjir bandang Jakarta',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'sedang',
        ]);

        $pegawai = Pegawai::create([
            'nama_pegawai' => 'Budi',
            'jabatan' => 'Staff Lapangan',
            'no_hp' => '08123456789',
            'alamat' => 'Jakarta',
            'status_aktif' => true,
        ]);

        $jadwal = JadwalLayanan::create([
            'bencana_id' => $bencana->id,
            'pegawai_id' => $pegawai->id_pegawai,
            'tanggal_layanan' => '2026-06-14',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'jenis_layanan' => 'Penyaluran Makanan',
            'sarana' => 'Mobil Ambulans',
            'petugas_lapangan' => 'Budi, Andi',
            'lokasi_layanan' => 'Posko 1',
            'status' => 'dijadwalkan',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.jadwal.index'));
        $response->assertStatus(200);
        $response->assertSee('Penyaluran Makanan');
    }

    public function test_admin_can_create_jadwal()
    {
        $this->withoutExceptionHandling();
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Gempa',
            'deskripsi' => 'Gempa Bumi',
        ]);

        $bencana = Bencana::create([
            'nama_bencana' => 'Gempa Sleman',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'parah',
        ]);

        $pegawai = Pegawai::create([
            'nama_pegawai' => 'Andi',
            'jabatan' => 'Supervisor',
            'no_hp' => '08987654321',
            'alamat' => 'Yogyakarta',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'bencana_id' => $bencana->id,
            'pegawai_id' => $pegawai->id_pegawai,
            'tanggal_layanan' => '2026-06-15',
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jenis_layanan' => 'Evakuasi Warga',
            'sarana' => 'Truk',
            'petugas_lapangan' => 'Andi',
            'lokasi_layanan' => 'Sleman',
            'status' => 'dijadwalkan',
        ]);

        $response->assertRedirect(route('admin.jadwal.index'));
        $this->assertDatabaseHas('jadwal', [
            'bencana_id' => $bencana->id,
            'pegawai_id' => $pegawai->id_pegawai,
            'jenis_layanan' => 'Evakuasi Warga',
        ]);
    }

    public function test_admin_can_edit_jadwal()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Longsor',
            'deskripsi' => 'Tanah Longsor',
        ]);

        $bencana = Bencana::create([
            'nama_bencana' => 'Longsor Bantul',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'ringan',
        ]);

        $pegawai = Pegawai::create([
            'nama_pegawai' => 'Candra',
            'jabatan' => 'Rescuer',
            'no_hp' => '081122334455',
            'alamat' => 'Bantul',
            'status_aktif' => true,
        ]);

        $jadwal = JadwalLayanan::create([
            'bencana_id' => $bencana->id,
            'pegawai_id' => $pegawai->id_pegawai,
            'tanggal_layanan' => '2026-06-14',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'jenis_layanan' => 'Pembersihan Jalan',
            'sarana' => 'Ekskavator',
            'petugas_lapangan' => 'Candra',
            'lokasi_layanan' => 'Bantul',
            'status' => 'dijadwalkan',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.jadwal.update', $jadwal->id), [
            'bencana_id' => $bencana->id,
            'pegawai_id' => $pegawai->id_pegawai,
            'tanggal_layanan' => '2026-06-14',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'jenis_layanan' => 'Pembersihan Jalan Selesai',
            'sarana' => 'Ekskavator',
            'petugas_lapangan' => 'Candra',
            'lokasi_layanan' => 'Bantul',
            'status' => 'selesai',
        ]);

        $response->assertRedirect(route('admin.jadwal.index'));
        $this->assertDatabaseHas('jadwal', [
            'id' => $jadwal->id,
            'jenis_layanan' => 'Pembersihan Jalan Selesai',
            'status' => 'selesai',
        ]);
    }

    public function test_admin_can_delete_jadwal()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Kebakaran',
            'deskripsi' => 'Kebakaran Hutan',
        ]);

        $bencana = Bencana::create([
            'nama_bencana' => 'Kebakaran Riau',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'parah',
        ]);

        $pegawai = Pegawai::create([
            'nama_pegawai' => 'Doni',
            'jabatan' => 'Pemadam',
            'no_hp' => '082233445566',
            'alamat' => 'Pekanbaru',
            'status_aktif' => true,
        ]);

        $jadwal = JadwalLayanan::create([
            'bencana_id' => $bencana->id,
            'pegawai_id' => $pegawai->id_pegawai,
            'tanggal_layanan' => '2026-06-14',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'jenis_layanan' => 'Pemadaman Api',
            'sarana' => 'Mobil Pemadam',
            'petugas_lapangan' => 'Doni',
            'lokasi_layanan' => 'Riau',
            'status' => 'dijadwalkan',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.jadwal.destroy', $jadwal->id));

        $response->assertRedirect(route('admin.jadwal.index'));
        $this->assertDatabaseMissing('jadwal', [
            'id' => $jadwal->id,
        ]);
    }

    public function test_admin_can_print_jadwal_pdf()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.jadwal.cetak'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}

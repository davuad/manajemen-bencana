<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\KategoriBencana;
use App\Models\Bencana;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BencanaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the admin role
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_non_admin_cannot_access_bencana()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.bencana.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_bencana_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Banjir',
            'deskripsi' => 'Banjir bandang',
        ]);

        Bencana::create([
            'nama_bencana' => 'Banjir bandang Jakarta',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'sedang',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.bencana.index'));
        $response->assertStatus(200);
        $response->assertSee('Banjir bandang Jakarta');
    }

    public function test_admin_can_create_bencana()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Gempa Bumi',
            'deskripsi' => 'Gempa tektonik/vulkanik',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.bencana.store'), [
            'nama_bencana' => 'Gempa Tektonik Sleman',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'parah',
        ]);

        $response->assertRedirect(route('admin.bencana.index'));
        $this->assertDatabaseHas('bencana', [
            'nama_bencana' => 'Gempa Tektonik Sleman',
            'kategori_id' => $kategori->id,
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'parah',
        ]);
    }

    public function test_admin_can_edit_bencana()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Tanah Longsor',
            'deskripsi' => 'Longsoran tanah',
        ]);

        $bencana = Bencana::create([
            'nama_bencana' => 'Tanah Longsor Kulon Progo',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'ringan',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.bencana.update', $bencana->id), [
            'nama_bencana' => 'Tanah Longsor Kulon Progo Updated',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-14',
            'status_bencana' => 'selesai',
            'tingkat_kerusakan' => 'sedang',
        ]);

        $response->assertRedirect(route('admin.bencana.index'));
        $this->assertDatabaseHas('bencana', [
            'id' => $bencana->id,
            'nama_bencana' => 'Tanah Longsor Kulon Progo Updated',
            'status_bencana' => 'selesai',
            'tingkat_kerusakan' => 'sedang',
        ]);
    }

    public function test_admin_can_delete_bencana()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Angin Puting Beliung',
            'deskripsi' => 'Angin kencang berputar',
        ]);

        $bencana = Bencana::create([
            'nama_bencana' => 'Angin Puting Beliung Bantul',
            'kategori_id' => $kategori->id,
            'tanggal' => '2026-06-13',
            'status_bencana' => 'berlangsung',
            'tingkat_kerusakan' => 'ringan',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.bencana.destroy', $bencana->id));

        $response->assertRedirect(route('admin.bencana.index'));
        $this->assertDatabaseMissing('bencana', [
            'id' => $bencana->id,
        ]);
    }
}

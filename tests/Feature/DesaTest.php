<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Desa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DesaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the admin role
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_non_admin_cannot_access_desa()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.desa.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_desa_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $desa = Desa::create([
            'nama_desa' => 'Desa Makmur',
            'kecamatan' => 'Kecamatan Jaya',
            'nama_kades' => 'Budi Santoso',
            'kontak_kades' => '081234567890',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.desa.index'));
        $response->assertStatus(200);
        $response->assertSee('Desa Makmur');
    }

    public function test_admin_can_create_desa()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.desa.store'), [
            'nama_desa' => 'Desa Damai',
            'kecamatan' => 'Kecamatan Sentosa',
            'nama_kades' => 'Siti Aminah',
            'kontak_kades' => '08987654321',
        ]);

        $response->assertRedirect(route('admin.desa.index'));
        $this->assertDatabaseHas('desa', [
            'nama_desa' => 'Desa Damai',
            'kecamatan' => 'Kecamatan Sentosa',
            'nama_kades' => 'Siti Aminah',
            'kontak_kades' => '08987654321',
        ]);
    }

    public function test_admin_can_view_desa_detail()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $desa = Desa::create([
            'nama_desa' => 'Desa Sejahtera',
            'kecamatan' => 'Kecamatan Makmur',
            'nama_kades' => 'Joko Widodo',
            'kontak_kades' => '08543210987',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.desa.detail', $desa->id));
        $response->assertStatus(200);
        $response->assertSee('Desa Sejahtera');
    }

    public function test_admin_can_edit_desa()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $desa = Desa::create([
            'nama_desa' => 'Desa Indah',
            'kecamatan' => 'Kecamatan Elok',
            'nama_kades' => 'Rahmat',
            'kontak_kades' => '08765432109',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.desa.update', $desa->id), [
            'nama_desa' => 'Desa Indah Updated',
            'kecamatan' => 'Kecamatan Elok Updated',
            'nama_kades' => 'Rahmat Updated',
            'kontak_kades' => '087654321090',
        ]);

        $response->assertRedirect(route('admin.desa.index'));
        $this->assertDatabaseHas('desa', [
            'id' => $desa->id,
            'nama_desa' => 'Desa Indah Updated',
            'kecamatan' => 'Kecamatan Elok Updated',
        ]);
    }

    public function test_admin_can_delete_desa()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $desa = Desa::create([
            'nama_desa' => 'Desa Hapus',
            'kecamatan' => 'Kecamatan Hilang',
            'nama_kades' => 'Supri',
            'kontak_kades' => '08122334455',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.desa.delete', $desa->id));

        $response->assertRedirect(route('admin.desa.index'));
        $this->assertDatabaseMissing('desa', [
            'id' => $desa->id,
        ]);
    }
}

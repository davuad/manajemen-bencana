<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Gudang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GudangTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_non_admin_cannot_access_gudang()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.gudang.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_gudang_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Gudang::create([
            'nama_gudang' => 'Gudang Utama Cilacap',
            'alamat' => 'Jl. Jend. Sudirman No. 12',
            'kapasitas' => 5000,
            'keterangan' => 'Gudang pusat logistik',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.gudang.index'));
        $response->assertStatus(200);
        $response->assertSee('Gudang Utama Cilacap');
    }

    public function test_admin_can_create_gudang()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.gudang.store'), [
            'nama_gudang' => 'Gudang Pembantu Majenang',
            'alamat' => 'Jl. Raya Majenang No. 5',
            'kapasitas' => 2000,
            'keterangan' => 'Gudang logistik wilayah barat',
        ]);

        $response->assertRedirect(route('admin.gudang.index'));
        $this->assertDatabaseHas('gudang', [
            'nama_gudang' => 'Gudang Pembantu Majenang',
            'alamat' => 'Jl. Raya Majenang No. 5',
            'kapasitas' => 2000,
            'keterangan' => 'Gudang logistik wilayah barat',
        ]);
    }

    public function test_admin_can_edit_gudang()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $gudang = Gudang::create([
            'nama_gudang' => 'Gudang A',
            'alamat' => 'Alamat A',
            'kapasitas' => 1000,
            'keterangan' => 'Ket A',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.gudang.update', $gudang->id), [
            'nama_gudang' => 'Gudang A Updated',
            'alamat' => 'Alamat A Updated',
            'kapasitas' => 1200,
            'keterangan' => 'Ket A Updated',
        ]);

        $response->assertRedirect(route('admin.gudang.index'));
        $this->assertDatabaseHas('gudang', [
            'id' => $gudang->id,
            'nama_gudang' => 'Gudang A Updated',
            'alamat' => 'Alamat A Updated',
            'kapasitas' => 1200,
            'keterangan' => 'Ket A Updated',
        ]);
    }

    public function test_admin_can_delete_gudang()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $gudang = Gudang::create([
            'nama_gudang' => 'Gudang B',
            'alamat' => 'Alamat B',
            'kapasitas' => 1000,
            'keterangan' => 'Ket B',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.gudang.destroy', $gudang->id));

        $response->assertRedirect(route('admin.gudang.index'));
        $this->assertDatabaseMissing('gudang', [
            'id' => $gudang->id,
        ]);
    }
}

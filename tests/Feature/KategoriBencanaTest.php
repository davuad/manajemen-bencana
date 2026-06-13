<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\KategoriBencana;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KategoriBencanaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the admin role
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_non_admin_cannot_access_kategori_bencana()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.kategori_bencana.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_kategori_bencana_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        KategoriBencana::create([
            'nama_kategori' => 'Banjir',
            'deskripsi' => 'Banjir bandang',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.kategori_bencana.index'));
        $response->assertStatus(200);
        $response->assertSee('Banjir');
    }

    public function test_admin_can_create_kategori_bencana()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.kategori_bencana.store'), [
            'nama_kategori' => 'Gempa Bumi',
            'deskripsi' => 'Gempa tektonik/vulkanik',
        ]);

        $response->assertRedirect(route('admin.kategori_bencana.index'));
        $this->assertDatabaseHas('kategori_bencana', [
            'nama_kategori' => 'Gempa Bumi',
            'deskripsi' => 'Gempa tektonik/vulkanik',
        ]);
    }

    public function test_admin_can_edit_kategori_bencana()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Tanah Longsor',
            'deskripsi' => 'Longsoran tanah',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.kategori_bencana.update', $kategori->id), [
            'nama_kategori' => 'Tanah Longsor Updated',
            'deskripsi' => 'Deskripsi updated',
        ]);

        $response->assertRedirect(route('admin.kategori_bencana.index'));
        $this->assertDatabaseHas('kategori_bencana', [
            'id' => $kategori->id,
            'nama_kategori' => 'Tanah Longsor Updated',
            'deskripsi' => 'Deskripsi updated',
        ]);
    }

    public function test_admin_can_delete_kategori_bencana()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $kategori = KategoriBencana::create([
            'nama_kategori' => 'Angin Puting Beliung',
            'deskripsi' => 'Angin kencang berputar',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.kategori_bencana.destroy', $kategori->id));

        $response->assertRedirect(route('admin.kategori_bencana.index'));
        $this->assertDatabaseMissing('kategori_bencana', [
            'id' => $kategori->id,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManagementUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the roles required
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'relawan']);
    }

    public function test_non_admin_cannot_access_management_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.management_user.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_management_user_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.management_user.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.management_user.store'), [
            'nama' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nik' => '1234567890123456',
            'no_wa' => '081234567890',
            'alamat' => 'Test Address',
            'role' => 'relawan',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('admin.management_user.index'));
        $this->assertDatabaseHas('user', [
            'nama' => 'Test User',
            'email' => 'testuser@example.com',
            'nik' => '1234567890123456',
            'no_wa' => '081234567890',
            'alamat' => 'Test Address',
        ]);

        $createdUser = User::where('email', 'testuser@example.com')->first();
        $this->assertTrue($createdUser->hasRole('relawan'));
    }

    public function test_admin_can_edit_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create([
            'nama' => 'Old Name',
            'email' => 'old@example.com',
            'nik' => '1111111111111111',
            'no_wa' => '081111111111',
            'alamat' => 'Old Address',
        ]);
        $user->assignRole('relawan');

        $response = $this->actingAs($admin)->put(route('admin.management_user.update', $user->id), [
            'nama' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'nik' => '2222222222222222',
            'no_wa' => '082222222222',
            'alamat' => 'Updated Address',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('admin.management_user.index'));
        $this->assertDatabaseHas('user', [
            'id' => $user->id,
            'nama' => 'Updated Name',
            'email' => 'updated@example.com',
            'nik' => '2222222222222222',
            'no_wa' => '082222222222',
            'alamat' => 'Updated Address',
        ]);

        $user->refresh();
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('relawan'));
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.management_user.destroy', $user->id));

        $response->assertRedirect(route('admin.management_user.index'));
        $this->assertDatabaseMissing('user', [
            'id' => $user->id,
        ]);
    }
}

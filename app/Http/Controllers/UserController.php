<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('manajemen_pengguna.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('manajemen_pengguna.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:user'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nik' => ['nullable', 'string', 'max:16', 'unique:user'],
            'no_wa' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('users', 'public');
        }

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'nik' => $validated['nik'] ?? null,
            'no_wa' => $validated['no_wa'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto' => $fotoPath,
            'status' => $validated['status'],
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.manajemen_user.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('manajemen_pengguna.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Cegah edit user dengan role admin
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.manajemen_user.index')
                ->with('error', 'Anda tidak dapat mengedit user admin');
        }

        $roles = Role::all();
        $userRole = $user->roles->first();

        return view('manajemen_pengguna.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:user,email,' . $user->id],
            'nik' => ['nullable', 'string', 'max:16', 'unique:user,nik,' . $user->id],
            'no_wa' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // ← Tambah ini
            'status' => ['required', 'in:aktif,nonaktif'],
            'role' => ['required', 'exists:roles,name'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Handle upload foto
        $fotoPath = $user->foto; // Tetap foto lama jika tidak ada upload baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            // Upload foto baru
            $fotoPath = $request->file('foto')->store('users', 'public');
        }

        $user->update([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'nik' => $validated['nik'] ?? null,
            'no_wa' => $validated['no_wa'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'foto' => $fotoPath, // ← Tambah ini
            'status' => $validated['status'],
        ]);

        if ($validated['password'] ?? null) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles($validated['role']);

        return redirect()->route('admin.manajemen_user.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.manajemen_user.index')
                ->with('error', 'Anda tidak dapat menghapus user admin');
        }

        $user->delete();
        return redirect()->route('admin.manajemen_user.index')
            ->with('success', 'User berhasil dihapus');
    }
}

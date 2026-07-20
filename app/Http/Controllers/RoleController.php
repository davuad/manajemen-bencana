<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $totalPermissions = Permission::count();

        return view('management_role.index', compact('roles', 'totalPermissions'));
    }

    public function create()
    {
        return view('management_role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('admin.management_role.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit($name)
    {
        $role = Role::where('name', $name)->with('permissions')->firstOrFail();
        $allPermissions = Permission::all();

        // Get CRUD flags from pivot
        $permissionCrud = [];
        foreach ($role->permissions as $perm) {
            $permissionCrud[$perm->id] = [
                'create' => $perm->pivot->create,
                'read' => $perm->pivot->read,
                'update' => $perm->pivot->update,
                'delete' => $perm->pivot->delete,
            ];
        }

        return view('management_role.edit', compact('role', 'allPermissions', 'permissionCrud'));
    }

    public function update(Request $request, $name)
    {
        $role = Role::where('name', $name)->firstOrFail();

        // Get all permission IDs
        $allPermIds = Permission::pluck('id')->toArray();

        // Detach all permissions first
        $role->permissions()->detach();

        // Attach permissions with CRUD flags
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permId => $crud) {
                if (in_array((int) $permId, $allPermIds)) {
                    $role->permissions()->attach($permId, [
                        'create' => $crud['create'] ?? false,
                        'read' => $crud['read'] ?? false,
                        'update' => $crud['update'] ?? false,
                        'delete' => $crud['delete'] ?? false,
                    ]);
                }
            }
        }

        // Clear Spatie cache
        app()['cache']->forget('spatie.permission.cache');

        return redirect()->route('admin.management_role.index')
            ->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy($name)
    {
        if ($name === 'admin') {
            return redirect()->route('admin.management_role.index')
                ->with('error', 'Role admin tidak bisa dihapus.');
        }

        $role = Role::where('name', $name)->firstOrFail();
        $role->permissions()->detach();
        $role->delete();

        // Clear Spatie cache
        app()['cache']->forget('spatie.permission.cache');

        return redirect()->route('admin.management_role.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}

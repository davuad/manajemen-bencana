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
}

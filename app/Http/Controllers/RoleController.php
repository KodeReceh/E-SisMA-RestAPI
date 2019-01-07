<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'success' => true,
            'data' => $roles
        ], 200);
    }

    public function get($id)
    {
        $role = Role::with('permissions')->find($id);

        return response()->json([
            'success' => true,
            'data' => $role
        ], 200);
    }

    public function store(Request $request)
    {
        $role = Role::create($request->all());

        if($request->filled('permission_ids')) {
            $role->permissions()->sync($request->permission_ids);
        }
        
        return response()->json([
            'success' => true,
            'data' => $role
        ], 201);
    }

    public function update($id, Request $request)
    {
        $role = Role::find($id);

        $role->update($request->all());

        $role->permissions()->sync($request->permission_ids);

        return response()->json([
            'success' => true,
            'data' => $role
        ], 201);
    }

    public function delete($id)
    {
        $role = Role::find($id);

        $role->delete();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil menghapus data.'
        ], 200);
    }

    public function getPermissions(Request $request)
    {
        if ($roleId = $request->input('role')) {
            $role = Role::find($roleId);
            $asignedPermissionIds = $role->permissions()->pluck('id');
            $permissions = Permission::whereNotIn('id',$asignedPermissionIds)->get();

            return response()->json([
                'success' => true,
                'data' => $permissions
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => Permission::all()
        ], 200);
    }
}

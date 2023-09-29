<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class RoleController extends Controller
{
    public function index() {
        $data = Role::orderBy('id','DESC')->get();
        
        return view('role.index',compact('data'));
    }

    public function create()
    {
        $permission = Permission::get();

        return view('role.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect(route('role'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = User::find($request->id);
        $permission = Permission::get();
        $selectedPermissions = DB::table('role_has_permissions')
        ->where('role_id', $data->jabatan)
        ->select('permission_id')
        ->get();
        $selectedPermissions = $selectedPermissions->pluck('permission_id')->toArray();

        return view('role.edit', Compact('data','permission','selectedPermissions'));
    }

    public function updated(Request $request, $id)
    {
        $this->validate($request, [
            'name'          => 'required',
            'permission'    => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect(route('role'))
                        ->with('success', 'Data berhasil disimpan');
    }
}

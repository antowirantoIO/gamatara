<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Roles;
use DB;

class RoleController extends Controller
{
    public function index() {
        $data = Role::orderBy('name','asc')->get();
        
        return view('role.index',compact('data'));
    }

    public function create()
    {
        $permission = Permission::orderBy('name','asc')->get();

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
        $data = Role::find($request->id);
        $permission = Permission::orderBy('name','asc')->get();
        $selectedPermissions = DB::table('role_has_permissions')
                                ->where('role_id', $data->id)
                                ->select('permission_id')
                                ->pluck('permission_id')->toArray();

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

    public function delete($id)
    {
        $data = Roles::findOrFail($id);

        if ($data->users()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak dapat menghapus data karena role masih digunakan di user'
            ]);
        }
    
        $data->delete();

        return redirect(route('role'))
                    ->with('success', 'Data berhasil dihapus');
    }

    
}

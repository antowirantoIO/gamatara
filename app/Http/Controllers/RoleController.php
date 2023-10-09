<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Exports\ExportRole;
use App\Models\User;
use App\Models\Roles;
use DB;

class RoleController extends Controller
{
    public function index(Request $request) 
    {
        if ($request->ajax()) {
            $data = Roles::orderBy('name','asc')
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function($data){
                return '<a href="'.route('role.edit', $data->id).'" class="btn btn-success btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                &nbsp;
                <a data-id="'.$data->id.'" data-name="role '.$data->name.'" data-form="form-role" class="btn btn-danger btn-sm deleteData">
                    <span>
                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                <form method="GET" id="form-role'.$data->id.'" action="'.route('role.delete', $data->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }
        
        return view('role.index');
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

    public function export(Request $request)
    {
        $data = Roles::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportRole($data), 'List Role.xlsx');
    }
}

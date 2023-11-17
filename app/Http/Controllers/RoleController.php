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
                $btnEdit = '';
                $btnDelete = '';
                if(Can('role-edit')) {
                    $btnEdit = '<a href="'.route('role.edit', $data->id).'" class="btn btn-success btn-sm">
                                <span>
                                    <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                                </span>
                            </a>';
                }
                if(Can('role-delete')){
                    $btnDelete = '<a data-id="'.$data->id.'" data-name="Role '.$data->name.'" data-form="form-role" class="btn btn-danger btn-sm deleteData">
                                    <span>
                                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>
                                <form method="GET" id="form-role'.$data->id.'" action="'.route('role.delete', $data->id).'">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                </form>';
                }
                return $btnEdit.'&nbsp;'.$btnDelete;
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }
        
        return view('role.index');
    }

    public function create()
    {
        $group_permission = Permission::select('menu_name')
            ->orderBy('menu_name', 'asc')
            ->groupBy('menu_name')
            ->get();

        $permission = Permission::get();
        $otherpermission = Permission::whereNull('sub_feature')->whereNull('feature')->get();
        $feature = Permission::whereNotNull('feature')->get();
        $sub = Permission::whereNotNull('sub_feature')->get();

        return view('role.create',compact('group_permission', 'permission', 'feature', 'sub', 'otherpermission'));
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
                    ->with('success', 'Data saved successfully');
    }

    public function edit(Request $request)
    {
        $role = Role::find($request->id);
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$request->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $group_permission = Permission::select('menu_name')
            ->orderBy('menu_name', 'asc')
            ->groupBy('menu_name')
            ->get();

        $permission = Permission::get();
        $otherpermission = Permission::whereNull('sub_feature')->whereNull('feature')->get();
        $feature = Permission::whereNotNull('feature')->get();
        $sub = Permission::whereNotNull('sub_feature')->get();

        return view('role.edit',compact('role','rolePermissions','group_permission', 'permission', 'feature', 'sub', 'otherpermission'));
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
                        ->with('success', 'Data saved successfully');
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
                    ->with('success', 'Data successfully deleted');
    }

    public function export(Request $request)
    {
        $data = Roles::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportRole($data), 'List Role.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Karyawan;
use App\Exports\ExportUser;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with(['role'])->orderBy('name','asc')
                    ->where('status',0)
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('name', function($data){
                return $data->karyawan->name ?? '';
            })
            ->addColumn('role', function($data){
                return $data->role->name ?? '';
            })
            ->addColumn('action', function($data){
                $btnEdit = '';
                $btnDelete = '';
                if($this->authorize('user-edit')) {
                    $btnEdit = '<a href="'.route('user.edit', $data->id).'" class="btn btn-success btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                    </span>
                </a>';
                }
                if($this->authorize('user-delete')){
                    ' <a data-id="'.$data->id.'" data-name="User '.$data->karyawan->name.'" data-form="form-user" class="btn btn-danger btn-sm deleteData">
                    <span>
                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                <form method="GET" id="form-user'.$data->id.'" action="'.route('user.delete', $data->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                </form>';
                }
                return $btnEdit.'&nbsp;'.$btnDelete;
            })
            ->rawColumns(['action','name'])
            ->make(true);                    
        }

        $karyawan = Karyawan::orderBy('name','asc')->get();
        $role = Role::orderBy('name','asc')->get();

        return view('user.index',compact('karyawan','role'));
    }

    public function create()
    {
        $karyawan   = Karyawan::orderBy('id','DESC')->get();
        $role       = Role::orderBy('id','DESC')->get();

        return view('user.create',compact('role','karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|unique:users',
            'karyawan'              => 'required',
            'password'              => 'required|min:6',
            'role'                  => 'required',
            'konfirmasi_password'   => 'required|same:password',
            'ttd'                   => 'required'
        ]);

        $data                   = New User();
        $data->email            = $request->input('email');
        $data->nomor_telpon     = $request->input('nomor_telpon');
        $data->id_karyawan      = $request->input('karyawan');
        $data->id_role          = $request->input('role');
        $data->ttd              = $request->input('ttd_base64');
        $data->password         = bcrypt($request->input('password'));
        $data->save();

        $data->assignRole($request->input('role'));

        return redirect(route('user'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data       = User::find($request->id);
        $karyawan   = Karyawan::orderBy('name','asc')->get();
        $role       = Role::orderBy('id','DESC')->get();

        return view('user.edit', Compact('data','karyawan','role'));
    }

    public function updated(Request $request,$id)
    {
        $request->validate([
            'email'                 => 'required|email|unique:users,email,'.$request->id,
            'role'                  => 'required',
            'karyawan'              => 'required',
        ]);

        if($request->password || $request->konfirmasi_password)
            $request->validate([
                'password'              => 'required|min:6',
                'konfirmasi_password'   => 'required|same:password',
            ]);
            
        $data = User::find($request->id);
 
        if($request->password)
            $password = bcrypt($request->input('password'));
        else
            $password = $data->password;
        
        $data->email        = $request->input('email');
        $data->nomor_telpon = $request->input('nomor_telpon');
        $data->id_karyawan  = $request->input('karyawan');
        $data->id_role      = $request->input('role');
        $data->ttd          = $request->input('ttd_base64');
        $data->password     = $password;
        $data->save();

        $data->assignRole($request->input('role'));

        return redirect(route('user'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = User::findOrFail($id);
        $data->status   = 1;
        $data->save();

        return redirect(route('user'))
                    ->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        $data = User::with(['role'])->orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportUser($data), 'List User.xlsx');
    }
}

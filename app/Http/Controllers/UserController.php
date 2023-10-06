<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
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
            ->addColumn('jabatan', function($data){
                return $data->role->name ?? '';
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('user.edit', $data->id).'" class="btn btn-success btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                &nbsp;
                <a data-id="'.$data->id.'" data-name="user '.$data->name.'" data-form="form-user" class="btn btn-danger btn-sm deleteData">
                    <span>
                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                <form method="GET" id="form-user'.$data->id.'" action="'.route('user.delete', $data->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        $role = Role::orderBy('id','DESC')->get();

        return view('user.index',compact('role'));
    }

    public function create()
    {
        $role = Role::orderBy('id','DESC')->get();

        return view('user.create',compact('role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users',
            'jabatan'               => 'required',
            'password'              => 'required|min:6',
            'konfirmasi_password'   => 'required|same:password',
        ]);

        $data                   = New User();
        $data->name             = $request->input('name');
        $data->email            = $request->input('email');
        $data->nomor_telpon     = $request->input('nomor_telpon');
        $data->jabatan          = $request->input('jabatan');
        $data->password         = bcrypt($request->input('password'));
        $data->save();

        $data->assignRole($request->input('jabatan'));

        return redirect(route('user'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = User::find($request->id);
        $role = Role::orderBy('id','DESC')->get();

        return view('user.edit', Compact('data','role'));
    }

    public function updated(Request $request,$id)
    {
        $request->validate([
            'name'                  => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'jabatan'               => 'required',
            'password'              => 'required|min:6',
            'konfirmasi_password'   => 'required|same:password',
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
        
        $data->name         = $request->input('name');
        $data->email        = $request->input('email');
        $data->nomor_telpon = $request->input('nomor_telpon');
        $data->jabatan      = $request->input('jabatan');
        $data->password     = $password;
        $data->save();

        $data->assignRole($request->input('jabatan'));

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

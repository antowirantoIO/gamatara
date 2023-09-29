<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $data = User::with(['role'])->where('status',0)->get();

        return view('user.index', Compact('data'));
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
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
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

}

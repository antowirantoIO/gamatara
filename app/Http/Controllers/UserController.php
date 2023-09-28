<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class UserController extends Controller
{
    public function index()
    {
        $data = Users::where('status',0)->get();

        return view('user.index', Compact('data'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6',
            'konfirmasi_password' => 'required|same:password',
        ]);

        $data                   = New Users();
        $data->name             = $request->input('name');
        $data->email            = $request->input('email');
        $data->nomor_telpon     = $request->input('nomor_telpon');
        $data->jabatan          = $request->input('jabatan');
        $data->password         = bcrypt($request->input('password'));
        $data->save();

        return redirect(route('user'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = Users::find($request->id);

        return view('user.edit', Compact('data'));
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
            
        $data = Users::find($request->id);
 
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

        return redirect(route('user'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = Users::findOrFail($id);
        $data->status   = 1;
        $data->save();

        return redirect(route('user'))
                    ->with('success', 'Data berhasil dihapus');
    }

}

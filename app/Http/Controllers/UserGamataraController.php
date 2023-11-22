<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserGamataraController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user()->id;
            $user = User::select('id', 'id_karyawan', 'id_role')
                    ->with(['role:id,name','karyawan:id,name'])->where('id',$user)
                    ->first();
            $user['name'] = $user->karyawan->name ?? '';
            if ($user->role->name == 'Project Enginer') {
               foreach($user->karyawan->pm ?? [] as $v){
                    foreach($v->pe as $value){
                        $user['id_karyawan'] = $value->id ?? '';
                    }
               }
            } elseif ($user->role->name == 'Project Manager') {
                $user['id_karyawan'] = $user->karyawan->pm->id ?? '';
            } else {
                $user['id_karyawan'] = '';
            }
            

            $token = auth()->user()->createToken('API Token')->accessToken;

            return response([
                'message' => 'success',
                'token' => $token,
                'user' => $user
            ]);
        }else{
            return response([
                    'message' => 'email & password salah, silahkan coba lagi!'
                ], 200
            );
        }
    }

    public function ubahPassword(Request $request)
    {
        $validasi = Validator::make($request->only(['email','password','password_konfirmasi']),[
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_konfirmasi' => 'required|min:8'
        ]);

        if($validasi->fails()){
            return response()->json(['status' => 500,'message' => $validasi->errors()->first()]);
        }

        if($request->password !== $request->password_konfirmasi){
            return response()->json(['statis' => 500,'message' => 'Password dan Password Konfirmasi Tidak Sama !'],500);
        }

        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json(['status' => 500,'message' => 'User Tidak Terdaftar!']);
        }

        User::where('email',$request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['status' => 200,'message' => 'Password Berhasil Di Ubah'],200);

    }
}

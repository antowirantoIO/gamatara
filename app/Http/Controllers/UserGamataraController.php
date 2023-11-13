<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Karyawan;

class UserGamataraController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user()->select('id','email','id_karyawan','id_role','nomor_telpon','status')->first();
            $user['role'] = Roles::find($user->id_role);
            $user['nama_pm'] = $user->karyawan->name ?? '';

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
}

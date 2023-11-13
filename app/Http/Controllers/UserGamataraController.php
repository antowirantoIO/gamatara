<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Karyawan;
use App\Models\User;

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

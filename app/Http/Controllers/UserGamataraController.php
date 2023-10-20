<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserGamataraController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

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

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\SendOtp;
use App\Models\OtpUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        try {
            $otpCode = mt_rand(1000, 9999);
            $email = $request->email;

            Mail::to($email)->send(new SendOtp($otpCode));
            OtpUser::create([
                'otp' => $otpCode,
                'otp_expired' => now()->addMinutes(5),
                'email' => $email
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'OTP Berhasil Di Kirim'
            ],200);



        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ],400);
        }

    }

    public function validationOtp(Request $request)
    {

        $validasi = Validator::make($request->only(['otp','email']),[
            'otp' => 'required',
            'email' => 'required|email'
        ]);

        if($validasi->fails()){
            return response()->json(['status' => 400,'message' => $validasi->errors()->first()],500);
        }

        $otp = OtpUser::where('otp', $request->otp)->first();
        if(empty($otp)){
            return response()->json(['status' => 400,'message' => 'OTP Tidak Valid'],400);
        }
        $email = $request->email;
        if($email != $otp->email){
            return response()->json(['status' => 400,'message' => 'OTP Tidak Valid']);
        }

        return response()->json(['status' => 200,'message' => 'OTP Valid'],200);
    }
}

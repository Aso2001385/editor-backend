<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\User;
use App\Models\UserVerifications;


class VerificationController extends Controller
{
    public function test(Request $request)
    {
        $mail_address=$request['email'];
        $text=$request['text'];
        $name=$request['name'];
        Mail::to($mail_address)->send(new TestMail($name,$text));
    }

    public function verificationCheck(Request $request)
    {
        $verification_info=UserVerifications::where('email','=',$request['email'])->first();
        if($verification_info!=null && $verification_info['code'] == $request['code']){
            User::create([
                'name'=>$verification_info['name'],
                'email'=>$verification_info['email'],
                'password'=>$verification_info['password'],
            ]);

            $verification_info->forceDelete();
            return response()->json(true, Response::HTTP_OK);
        }

        // return response()->json($verification_info, Response::HTTP_OK);
        return response()->json('CODEが間違っています　再度入力してください', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function reSend($email)
    {
        $verification_info=UserVerifications::where('email','=',$email)->first();
        if($verification_info['code'] == $request['code'] && isset($verification_info)){
            User::create([
                'name'=>$verification_info['name'],
                'email'=>$verification_info['email'],
                'password'=>$verification_info['password'],
            ]);

            $verification_info->forceDelete();
            return response()->json(true, Response::HTTP_OK);
        }

        // return response()->json($verification_info, Response::HTTP_OK);
        return response()->json('CODEが間違っています　再度入力してください', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

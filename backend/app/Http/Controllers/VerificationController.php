<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Design;
use App\Models\UserVerifications;
use App\Mail\TestMail;
use App\Mail\VerificationMail;
use App\Commons\DesignCommon;

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
        $temp=DesignCommon::design();
        if($verification_info!=null && $verification_info['code'] == $request['code']){
            $user = User::create([
                'name'=>$verification_info['name'],
                'email'=>$verification_info['email'],
                'password'=>$verification_info['password'],
            ]);
            $newDesign = Design::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'name' => 'Design1',
                'point' => 0,
                'contents' => $temp,
            ]);

          

            $verification_info->forceDelete();
            return response()->json($user, Response::HTTP_OK);
        }

        // return response()->json($verification_info, Response::HTTP_OK);
        return response()->json('CODEが間違っています 再度入力してください', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function reSend($email)
    {

        $code=str_pad(random_int(0,999999),6,0, STR_PAD_LEFT);
        $userVerification=UserVerifications::updateOrCreate(['email'=>$email],['code'=>$code]);
        $mail_address = $email;
        $name = $userVerification->name;
        $text = $userVerification->name."さんのcodeは".$userVerification->code."です。";
        Mail::to($mail_address)->send(new VerificationMail($name,$text));
        return response()->json(['emial'=>$email], Response::HTTP_OK);
    }
}

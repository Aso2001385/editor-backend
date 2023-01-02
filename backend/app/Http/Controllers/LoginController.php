<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class LoginController extends Controller
{

    public function testGet(Request $request){
        $user=User::find($request['user_id']);
        return response()->json($user['name'],200);
    }


    public function testPost(Request $request){
        return response()->json($request,200);
    }

    public function login(UserLoginRequest $request){

        try{
            $user = User::where('email',$request['email'])->first();
            if($user==null) throw new Exception;
            session(['user' => $user]);
            if (!(Hash::check($request['password'], $user['password']))) throw new Exception;

        }catch(Exception $e){
            return response()->json($e,Response::HTTP_UNAUTHORIZED);
        }
        return response()->json($user,Response::HTTP_OK)->header('Access-Control-Allow-Origin','*');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return response()->json(true, Response::HTTP_OK);
    }
}

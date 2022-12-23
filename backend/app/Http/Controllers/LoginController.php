<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;

class LoginController extends Controller
{

    public function testGet(Request $request){
        $user=User::find($request['user_id']);
        return response()->json($user['name'],200);
    }


    public function testPost(Request $request){
        return response()->json($request,200);
    }

    public function login(UserLoginRequest $request)
    {
        $user=User::findOrFail($request['id']);
        if (Hash::check($request['password'],$user['password'])) {
            session(['user' => $user]);
            return response()->json($user, Response::HTTP_OK);
        }
        return response()->json([], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return response()->json(true, Response::HTTP_OK);
    }
}

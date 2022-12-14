<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;

class LoginController extends Controller
{

    public function testGet(Request $request){
        return response()->json(Auth::user()->name,200);
    }

    public function testPost(Request $request){
        return response()->json($request,200);
    }

    public function login(UserLoginRequest $request)
    {
        if (Auth::attempt($request->toArray())) {
            $request->session()->regenerate();
            $request->session()->token();
            return response()->json(Auth::user(), Response::HTTP_OK);
        }
        return response()->json([], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(true, Response::HTTP_OK);
    }
}

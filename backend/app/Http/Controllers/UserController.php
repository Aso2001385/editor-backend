<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Hashing\HashManager;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //user一覧
        $users = User::all()->toArray();

        return response()->json($users, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //user作成
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->all());

        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        $user->projects;
        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $user->User::update($request->all());

        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        $result = true;

        return response()->json($result, Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $users = 'NOTHING';

        if (isset($request['id']) && isset($request['name'])) {
            $users = User::where('id', '=', $request['id'])->andWhere('name LIKE', '%' . $request['name'] . '%')->all()->toArray();
        } else if (isset($request['id'])) {
            $users = User::find($request['id']);
        } else if (isset($request['id'])) {
            $users = User::where('name LIKE', '%' . $request['name'] . '%')->all()->toArray();
        }


        return response()->json($users, Response::HTTP_OK);
    }
}

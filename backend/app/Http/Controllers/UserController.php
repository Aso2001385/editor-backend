<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Hashing\HashManager;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\PasswordUpdateUserRequest;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserSearchRequest;

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
        $user->update($request->all());
        return response()->json($user, Response::HTTP_OK);
    }

    public function passUpdate(Request $request)
    {
        $result = true;

        return response()->json($result, Response::HTTP_OK);
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
        try {
            if (strlen($request['id']) > 0 && strlen($request['email']) > 0 && strlen($request['name']) > 0) {
                $users['users'] = User::where('id', '=', $request['id'])->where('email', 'LIKE', '%' . $request['email'] . '%')->where('name', 'LIKE', '%' . $request['name'] . '%')->get();
            } else if (strlen($request['email']) > 0 && strlen($request['name']) > 0) {
                $users['users'] = User::where('email', 'LIKE', '%' . $request['email'] . '%')->where('name', 'LIKE', '%' . $request['name'] . '%')->get();
            } else if (strlen($request['id']) > 0) {
                $users['users'] = User::find($request['id'])->get();
            } else if (strlen($request['email']) > 0) {
                $users['users'] = User::where('email', 'LIKE', '%' . $request['email'] . '%')->get();
            } else if (strlen($request['name']) > 0) {
                $users['users'] = User::where('name', 'LIKE', '%' . $request['name'] . '%')->get();
            }

            $users['count'] = count($users['users']);
        } catch (Exception $e) {
            return response()->json($e, $e->getCode());
        }

        return response()->json($users, Response::HTTP_OK);
    }
}

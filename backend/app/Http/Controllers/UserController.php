<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Auth;
use App\Mail\VerificationMail;
use App\Models\User;
use App\Models\UserVerifications;
use App\Models\Design;
use App\Models\UserDesign;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserDesignResource;
use App\Http\Resources\UserProjectResource;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Hashing\HashManager;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\PasswordUpdateUserRequest;
use App\Http\Requests\UserUpdateRequest;
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
    public function store(CreateUserRequest $request)
    {
        $user_verificated=UserVerifications::where('email','=',$request['email'])->forceDelete();
        $request['code']=str_pad(random_int(0,999999),6,0, STR_PAD_LEFT);
        $request['password'] = Hash::make($request->password);
        $token=UserVerifications::create($request->all());
        $mail_address = $token['email'];
        $name=$request['name'];
        $text=$token['name']."さんのcodeは".$request['code']."です。";
        Mail::to($mail_address)->send(new VerificationMail($name,$text));
        return response()->json($token, Response::HTTP_OK);
    }

    public function register(CreateUserRequest $request)
    {
        $request['password']=Hash::make($request->password);
        $user=User::create($request->all());

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
        //$user->projects;
        $user = new UserResource(User::findOrFail($user->id));
        return response()->json(new UserResource(User::findOrFail($user->id)), Response::HTTP_OK);
    }

    public function designs()
    {
        return response()->json(new UserDesignResource(User::findOrFail(Auth::id())), Response::HTTP_OK);
    }

    public function projects()
    {
        return response()->json(new UserProjectResource(User::findOrFail(Auth::id())), Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserEditRequest $request, User $user)
    {
        //
        $user->update($request->all());
        return response()->json($user, Response::HTTP_OK);
    }

    public function passwordUpdate(PasswordUpdateUserRequest $request)
    {
        $user=User::find($request['id']);
        if(!Hash::check($request->old_password,$user->password)){
            return response()->json(false, Response::HTTP_UNAUTHORIZED);
        }
        $user->password=Hash::make($request->new_password);;
        $user->save();
        return response()->json(true, Response::HTTP_OK);
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

    public function search(UserSearchRequest $request)
    {
        try {
            $id = User::where('id', '=', $request['id']);
            $email = User::where('email', 'LIKE', '%' . $request['email'] . '%');
            $name = User::where('name', '=',  $request['name']);

            $users['users']['id'] = $id->get();
            $users['users']['email'] = $email->get();
            $users['users']['name'] = $name->get();


            $users['count'] = count($users['users']['id']) + count($users['users']['email']) + count($users['users']['name']);
        } catch (Exception $e) {
            return response()->json($e, $e->getCode());
        }

        return response()->json($users, Response::HTTP_OK);
    }
}

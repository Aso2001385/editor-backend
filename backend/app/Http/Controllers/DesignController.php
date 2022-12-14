<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Design;
use App\Models\UserDesign;
use App\Models\ProjectDesign;
use App\Models\ProjectUser;
use App\Http\Requests\CreateDesignRequest;
use App\Http\Requests\DesignUpdateRequest;
use App\Http\Resources\DesignCollection;
use App\Http\Resources\DesignResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designs = User::findOrFail(Auth::id())->designs;

        return response()->json(new DesignCollection($designs), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDesignRequest $request)
    {
        //
        $request['user_id'] = Auth::id();
        $request['uuid'] = (string) Str::uuid();
        logger()->error($request->except(['contents']));
        $design = Design::create($request->all());
        UserDesign::create([
            'design_id' => $design->id,
            'user_id' => $design->user_id,
        ]);
        $projects = ProjectUser::where('user_id','=',Auth::id())->get();
        if(isset($projects)){
            foreach($projects as $project){
                ProjectDesign::create([
                    'project_id'=>$project['project_id'],
                    'design_id'=>$design['id'],
                ]);
            }
        }
        return response()->json($design, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try{
            $design=Design::where('uuid',$id)->firstOrFail();
            return response()->json(new DesignResource($design), Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return response()->json($e, Response::HTTP_NOT_FOUND);
        }

    }

    public function buy($id)
    {
        if(isset(Design::where('uuid','=',$id)->first()['id'])){
            $design=Design::where('uuid','=',$id)->first();
            $user = User::find(Auth::id());
            $user['point'] = $user['point'] - $design['point'];

            if ($user['point'] >= 0) {
                $design_user_info = [
                    'design_id' => $design['id'],
                    'user_id' => auth()->user()->id
                ];

                $design_user = UserDesign::create($design_user_info);

                $user->update(['point' => $user['point']]);
            } else {
                return response()->json('Not Enough', Response::HTTP_I_AM_A_TEAPOT);
            }

            return response()->json($design, Response::HTTP_OK);
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DesignUpdateRequest $request, $id)
    {
        try{
            $design=Design::where('uuid',$id)->firstOrFail();
            if($design['user_id'] == Auth::id()){
                $preview = $request->preview;
                $path = 'previews/designs/'.$design->uuid.'.txt';
                Storage::put($path, $preview);
                $design->update($request->except('preview'));
                return response()->json(new DesignResource($design), Response::HTTP_OK);
            }
            return response()->json('',401);
        }catch(ModelNotFoundException $e){
            return response()->json($e, Response::HTTP_NOT_FOUND);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(isset(Design::where('uuid','=',$id)->first()['id'])){
            $design=Design::where('uuid','=',$id)->first();
            $design->delete();

            return response()->json(true, Response::HTTP_OK);
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
    }



    // public function gacha(Request $request)
    // {
    //     $point = User::select('point')->where('id',$user_id)->first()['point'];

    // //??????????????????????????????????????????????????????
    // if($point >= 100)
    // {
    //     $designs = Design::select('id','point')->get();
    //     $user_designs_id = UserDesign::select('design_id')->where('user_id',$user_id)->get();
    //     $total_design =  collect([]);

    //     //Design???????????????ID???UserDesign???????????????ID?????????
    //     foreach($designs as $design )
    //     {
    //         foreach($user_designs_id as $user_design_id)
    //         {
    //             if($design->id != $user_design_id->design_id)
    //             {
    //                 $sort_total_design = $total_design->concat(['id' => $design->id])->concat(['point' => $design->point]);
    //             }
    //         }
    //     }

    //     if($sort_total_design.isNotEmpty())
    //     {

    //         //total_design???point????????????????????????
    //         $total_design = $sort_total_design->sortByDesc('point')->values()->toArray();

    //         //???????????????
    //         $rarities =  [
    //             'SR' => 0.1,
    //             'R'  => 0.3,
    //             'N' => 0.6
    //         ];

    //         //total_design??????????????????????????????????????????

    //         $i = 0;
    //         foreach ($rarities as $key => $weight){
    //             $rarities2[$i] = array(
    //                 $key => $weight*$total_design,
    //             );
    //             $i++;
    //         }

    //         //???????????????????????????????????????????????????????????????$result_number
    //         $total_weight = 0;
    //         $result_number = random_int(0,array_sum($rarities2)-1);

    //         foreach ($rarities2 as $name => $weight)
    //         {
    //             $total_weight += $weight;
    //             if($result_number <= $total_weight)
    //             {
    //                 $result_rarities = $name;
    //                 break;
    //             }
    //         }
    //         //????????????????????????$total_design????????????????????????
    //         $total_design->each(function ($design,$key,$result_number){
    //             if($key == $result_number)
    //             {
    //                 return $design['id'];
    //             }

    //         });
    //         Auth::id();

    //         //??????????????????User???point???100???????????????????????????
    //         $point -= 100;
    //         $update = UserDesign::where('id',$user_id)->first();
    //         $update->point = $point;
    //         $update->save();

    //         //?????????????????????design_id??????????????????user_id?????????

    //         UserDesign::create([
    //             'user_id' => $user_id,
    //             'design_id' => $design['id'],
    //         ]);

    //         //????????????????????????????????????????????????????????????????????????
    //         foreach($designs as $design )
    //         {
    //             foreach($user_designs_id as $user_design_id)
    //             {
    //                 if($design->id != $user_design_id->design_id)
    //                 {
    //                     $sort_total_design = $total_design->concat(['id' => $design->id])->concat(['point' => $design->point]);
    //                 }
    //             }
    //         }
    //         if($sort_total_design.isEmpty())
    //         {
    //             $message = '?????????????????????????????????';
    //             UserDesign::create([
    //                 'user_id' => $user_id,
    //                 'design_id' => '51',
    //             ]);
    //         }

    //     }else{
    //         $message = '???????????????????????????';
    //     }

    // }else{
    //     $message = '???????????????????????????';
    // }

    // return response()->json($design, Response::HTTP_OK);
    // }



}




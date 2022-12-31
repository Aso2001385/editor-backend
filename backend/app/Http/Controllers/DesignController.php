<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Design;
use App\Models\UserDesign;
use App\Models\ProjectUser;
use App\Models\ProjectDesign;
use Exception;
use App\Http\Requests\CreateDesignRequest;
use App\Http\Requests\DesignUpdateRequest;
use Illuminate\Support\Collection;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designs = Design::all()->toArray();

        return response()->json($designs, Response::HTTP_OK);
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
        $request['user_id']=Auth::id();
        $design = Design::create($request->all());
        UserDesign::create([
            'design_id' => $design['id'],
            'user_id' => $design['user_id'],
        ]);
        $projects=ProjectUser::where('user_id','=',Auth::id())->get();
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
        if(isset(Design::where('uuid','=',$id)->first()['id'])){
            $design=Design::where('uuid','=',$id)->first();
            return response()->json($design, Response::HTTP_OK);
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
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



    // public function gacha(Request $request)
    // {
    //     $point = User::select('point')->where('id',$user_id)->first()['point'];

    // //ポイントが１００ポイントの以上の場合
    // if($point >= 100)
    // {
    //     $designs = Design::select('id','point')->get();
    //     $user_designs_id = UserDesign::select('design_id')->where('user_id',$user_id)->get();
    //     $total_design =  collect([]);

    //     //DesignのデザインIDとUserDesignのデザインIDを比較
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

    //         //total_designのpointを降順に並べ替え
    //         $total_design = $sort_total_design->sortByDesc('point')->values()->toArray();

    //         //排出率設定
    //         $rarities =  [
    //             'SR' => 0.1,
    //             'R'  => 0.3,
    //             'N' => 0.6
    //         ];

    //         //total_designの総数に応じて排出率を決める

    //         $i = 0;
    //         foreach ($rarities as $key => $weight){
    //             $rarities2[$i] = array(
    //                 $key => $weight*$total_design,
    //             );
    //             $i++;
    //         }

    //         //ここから全部メソッドにしたいです！返り値は$result_number
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
    //         //乱数で出た数値と$total_designの要素を比較する
    //         $total_design->each(function ($design,$key,$result_number){
    //             if($key == $result_number)
    //             {
    //                 return $design['id'];
    //             }

    //         });
    //         Auth::id();

    //         //データベースUserのpointを100引いたものに変える
    //         $point -= 100;
    //         $update = UserDesign::where('id',$user_id)->first();
    //         $update->point = $point;
    //         $update->save();

    //         //ガチャで引いたdesign_idとユーザーのuser_idを保存

    //         UserDesign::create([
    //             'user_id' => $user_id,
    //             'design_id' => $design['id'],
    //         ]);

    //         //コンプリートして、５１個目のデザインが排出される
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
    //             $message = 'コンプリートしました！';
    //             UserDesign::create([
    //                 'user_id' => $user_id,
    //                 'design_id' => '51',
    //             ]);
    //         }

    //     }else{
    //         $message = 'ガチャ引けません！';
    //     }

    // }else{
    //     $message = 'ガチャ引けません！';
    // }

    // return response()->json($design, Response::HTTP_OK);
    // }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DesignUpdateRequest $request, $id)
    {
        //
        if(isset(Design::where('uuid','=',$id)->first()['id'])){
            $design=Design::where('uuid','=',$id)->first();
            $design->update($request->all());
            return response()->json($design, Response::HTTP_OK);
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
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
}

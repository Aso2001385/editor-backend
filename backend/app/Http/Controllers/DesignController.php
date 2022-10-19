<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Design;
use App\Models\UserDesign;

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
    public function store(Request $request)
    {
        //
        $design = Design::create($request->all());
        $design_user_info = [
            'design_id' => $design['id'],
            'user_id' => $design['user_id']
        ];
        $design_user = UserDesign::create($design_user_info);

        return response()->json($design, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Design $design)
    {
        //
    }

    public function buy(Design $design)
    {
        $design_user_info = [
            'design_id' => auth()->user()->id,
            'user_id' => $design['user_id']
        ];
        $design_user = UserDesign::create($design_user_info);

        return response()->json($design, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Design $design)
    {
        //
        $design->update($request->all());
        return response()->json($design, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Design $design)
    {
        $design->delete();
        $result = true;

        return response()->json($result, Response::HTTP_OK);
    }
}

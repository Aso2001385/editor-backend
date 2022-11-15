<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use app\Models\Project;
use app\Models\ProjectUser;
use app\Models\ProjectDesign;
use app\Models\UserDesign;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\ProjectCopyRequest;
use App\Http\Requests\ProjectUpdateRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //プロジェクトテーブルから全件取得
        $projects = Project::all()->toArray();

        return response()->json($projects, Response::HTTP_OK);
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
        $project = Project::create($request->all());
        $project_user_info = [
            'project_id' => $project['id'],
            'user_id' => $project['user_id']
        ];
        ProjectUser::create($project_user_info);

        $user_designs = UserDesign::where('user_id', '=', $project['user_id'])->select('design_id')->get();

        foreach ($user_designs as $user_design) {
            $project_design_info = [
                'design_id' => $user_design['design_id'],
                'project_id' => $project['id']
            ];
            ProjectDesign::create($project_design_info);
        }

        return response()->json($project, Response::HTTP_OK);
    }

    public function copy(Project $project)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //

        return response()->json($project, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
        $project->update($request->all());
        return response()->json($project, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
        $project->delete();
        $result = true;
        return response()->json($result, Response::HTTP_OK);
    }
}

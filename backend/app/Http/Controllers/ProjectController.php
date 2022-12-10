<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectDesign;
use App\Models\UserDesign;
use App\Models\Page;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\ProjectCopyRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;

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
    public function store(CreateProjectRequest $request)
    {
        //
        $request['uuid']=(string) Str::uuid();
        $project = Project::create($request->all());

        ProjectUser::create([
            'project_id' => $project['id'],
            'user_id' => $project['user_id']
        ]);

        $page=Page::create([
            'project_id'=>$project['id'],
            'number'=>1,
            'user_id'=>$project['user_id'],
            'design_id'=>1,
            'title'=>'新規ページ',
            'contents'=>'# 新規ページ',
        ]);

        $user_designs = UserDesign::where('user_id', '=', $project['user_id'])->select('design_id')->get();

        foreach ($user_designs as $user_design) {
            ProjectDesign::create([
                'design_id' => $user_design['design_id'],
                'project_id' => $project['id']
            ]);
        }

        return response()->json(new ProjectResource($project), Response::HTTP_OK);
    }

    public function copy($id,ProjectCopyRequest $request)
    {
        $project=Project::where('uuid','=',$id)->first();
        $pages=Page::where('project_id','=',$project['id'])->get();
        $project=Project::create([
            'uuid'=>(string) Str::uuid(),
            'user_id'=>11,
            'name'=>$request['name'],
            'ui'=>$project['ui']
        ]);

        ProjectUser::create([
            'project_id'=>$project['id'],
            'user_id'=>11
        ]);

        $user_designs=UserDesign::where('user_id','=',11)->get();

        foreach($user_designs as $user_design)
        {
            ProjectDesign::create([
                'project_id'=>$project_id,
                'design_id'=>$user_design['design_id']
            ]);
        }

        foreach($pages as $page)
        {
            $pages_info=[
                'project_id'=>$project['id'],
                'number'=>$page['number'],
                'user_id'=>11,
                'design_id'=>$page['design_id'],
                'title'=>$page['title'],
                'contents'=>$page['contents']
            ];

            $user_design=UserDesign::where('design_id','=',$page['design_id'])->get();

            if(count($user_design)==0)
            {
                $pages_info['design_id']=UserDesign::where('user_id','=',11)->min('design_id');
            }

            Page::create($pages_info);
        }
        return response()->json($project, Response::HTTP_OK);
    }

    public function save(Request $request)
    {   
        $request['user_id']=11;
        $page=Page::updateOrCreate(['project_id'=>$request['project_id'],'number'=>$request['number']],$request->all());
        return response()->json($page, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $project = new ProjectResource(Project::where('uuid','=',$id)->first());
        return response()->json($project, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectUpdateRequest $request, $id)
    {
        //
        $project=Project::where('uuid','=',$id)->first();
        $project->update($request->all());
        return response()->json($project, Response::HTTP_OK);
    }

    public function pageDelete($id)
    {
        $page=Page::findOrFail($id);
        $project_id=Project::select('id')->where('id','=',$page['project_id'])->first()['id'];
        $page_count=count(Page::where('project_id','=',$project_id)->get())-1;
        if($page_count==0)
        {
            return response()->json("これ以上削除出来ません", Response::HTTP_ACCEPTED);
        }
        $above_pages=Page::where('project_id','=',$project_id)->where('number','>',$page['number'])->get();
        $page->forceDelete();
        if(count($above_pages)!=0)
        {
            foreach($above_pages as $above_page)
            {
                $above_page['number']-=1;
                $above_page->save();
            }
        }
        return response()->json(true, Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $project=Project::where('uuid','=',$id)->first();
        $pages=Page::where('project_id','=',$project->id);
        foreach($pages as $page){
            $page->delete();
        }
        $project->delete();
        return response()->json(true, Response::HTTP_ACCEPTED);
    }
}

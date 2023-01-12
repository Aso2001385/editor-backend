<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Commons\Markdown;
use App\Commons\CodeTemplate;
use App\Commons\NeoZip;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectDesign;
use App\Models\User;
use App\Models\UserDesign;
use App\Models\Page;
use App\Models\Design;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\ProjectCopyRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\PageResource;
use App\Http\Resources\PagesResource;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;

use Exception;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = User::findOrFail(Auth::id())->projects;
        return response()->json(new ProjectCollection($projects), Response::HTTP_OK);
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
        $request['user_id']=Auth::id();
        $request['uuid']=(string) Str::uuid();
        $project = Project::create($request->all());

        ProjectUser::create([
            'project_id' => $project['id'],
            'user_id' => $project['user_id']
        ]);

        $page = Page::create([
            'project_id'=>$project['id'],
            'user_id'=>$project['user_id'],
            'design_id'=>1,
            'number'=>1,
            'title'=>'新規ページ',
            'contents'=>'# 新規ページ',
        ]);

        $user_designs = UserDesign::where('user_id', $project->user_id)->select('design_id')->get();

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

            $project=Project::where('uuid',$id)->firstOrFail();
            $pages=Page::where('project_id','=',$project['id'])->get();
            $project=Project::create([
                'uuid'=>(string) Str::uuid(),
                'user_id'=>Auth::id(),
                'name'=>$request['name'],
                'ui'=>$project['ui']
            ]);

            ProjectUser::create([
                'project_id'=>$project['id'],
                'user_id'=>Auth::id()
            ]);

            $user_designs=UserDesign::where('user_id','=',Auth::id())->get();

            foreach($user_designs as $user_design)
            {
                ProjectDesign::create([
                    'project_id'=>$project->id,
                    'design_id'=>$user_design['design_id']
                ]);
            }

            foreach($pages as $page)
            {
                $pages_info=[
                    'project_id'=>$project['id'],
                    'number'=>$page['number'],
                    'user_id'=>Auth::id(),
                    'design_id'=>$page['design_id'],
                    'title'=>$page['title'],
                    'contents'=>$page['contents']
                ];

                $user_design=UserDesign::where('design_id','=',$page['design_id'])->get();

                if(count($user_design)==0)
                {
                    $pages_info['design_id']=UserDesign::where('user_id','=',Auth::id())->min('design_id');
                }

                Page::create($pages_info);
            }
            return response()->json($project, Response::HTTP_OK);

    }

    public function save(Request $request)
    {

        try {

            $project_id = Project::where('uuid',$request['project_uuid'])->firstOrFail()->id;
            $request['user_id'] = Auth::id();


            $request['design_id']=Design::where('uuid',$request['design_uuid'])->firstOrFail()->id;
            $request['design_id']=Design::where('uuid',$request['design_uuid'])->firstOrFail()->id;
            $page = Page::updateOrCreate(['project_id'=>$request['project_id'],'number'=>$request['number']],$request->except(['project_uuid','design_uuid']));
            return response()->json(new PageResource($page), Response::HTTP_OK);

        } catch (Exception $e) {

            return response()->json($e, Response::HTTP_NOT_FOUND);

        }


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
        if(isset(Project::where('uuid','=',$id)->first()['id'])){
            $project = new ProjectResource(Project::where('uuid','=',$id)->first());
            return response()->json($project, Response::HTTP_OK);
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
    public function update(ProjectUpdateRequest $request, $id)
    {
        //
        if(isset(Project::where('uuid','=',$id)->first()['id'])){
            $project=Project::where('uuid','=',$id)->first();
            $request['user_id']=Auth::id();
            $project->update($request->all());
            return response()->json(new ProjectResource($project), Response::HTTP_OK);
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
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
        if(isset(Project::where('uuid','=',$id)->first()['id'])){
            $project=Project::where('uuid','=',$id)->first();
            $pages=Page::where('project_id','=',$project->id);
            foreach($pages as $page){
                $page->delete();
            }
            $project->delete();
            return response()->json(true, Response::HTTP_OK);
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
    }


    public function export($id)
    {
        try{

            $project = Project::where('uuid',$id)->firstOrFail();
            $pages = Page::where('project_id',$project->id)->get();

            $make_path = 'projects/exports/'.$project['name'];
            $neo = new NeoZip(storage_path('app/projects/zips/'.$project['name'].'.zip'),$make_path);
            Storage::disk('local');
            Storage::makeDirectory($make_path);
            Storage::makeDirectory($make_path.'/css');
            Storage::makeDirectory($make_path.'/js');
            Storage::makeDirectory($make_path.'/assets/designs');
            Storage::makeDirectory($make_path.'/assets/images');

            $settings = [];

            $lis = CodeTemplate::itemSet($pages);

            foreach($pages as $index => $page){
                $number = $index + 1;
                $text = Markdown::get($page['contents']);
                $design = Design::find($page['design_id']);
                $neo->put('assets/designs/'.$design['uuid'].'.json', $design['contents']);
                $settings[$page['number']] = [
                    'name' => $page['title'],
                    'design' => 'design_'.$number,
                    'number' => $page['number']
                ];
                $page['response'] = CodeTemplate::htmlSet($text,$page['title'],$design['uuid'],$lis);
                $neo->put($page['title'].'_'.$page['number'].'.html', $page['response']);
            }

            $neo->put('assets/settings.json', json_encode($settings));
            $neo->copy('projects/templates/js/design-setting.js','js/design-setting.js');
            $neo->copy('projects/templates/js/parts/header.js','js/parts/header.js');
            $neo->copy('projects/templates/css/sanitize.css','css/sanitize.css');
            $neo->copy('projects/templates/css/variable.css','css/variable.css');
            $neo->copy('projects/templates/css/parts/menu.css','css/parts/menu.css');
            $neo->copy('projects/templates/images/language-html5.png','assets/images/language-html5.png');

            return response()->download($neo->close())->deleteFileAfterSend();

        }catch(\GuzzleHttp\Exception\ConnectException $e){
            return response()->json($e->getHandlerContext(), Response::HTTP_BAD_REQUEST);
        }catch(\GuzzleHttp\Exception\RequestException $e){
            return response()->json($e->getHandlerContext(), Response::HTTP_BAD_REQUEST);
        }catch(Exception $e){
            return response()->json($e, $e->getCode());
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
    }

}

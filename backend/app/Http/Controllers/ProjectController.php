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
use App\Models\Design;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\ProjectCopyRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
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
        $request['user_id']=Auth::id();
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
        if(isset(Project::where('uuid','=',$id)->first()['id'])){
            $project=Project::where('uuid','=',$id)->first();
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
                    'project_id'=>$project_id,
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
        return response()->json(false, Response::HTTP_NOT_FOUND);
    }

    public function save(Request $request)
    {   
        if(isset(Project::where('uuid','=',$request['uuid'])->first()['id'])){
            $request['project_id']=Project::where('uuid','=',$request['uuid'])->first()['id'];
            $request['user_id']=Auth::id();
            unset($request['uuid']);
            $page=Page::updateOrCreate(['project_id'=>$request['project_id'],'number'=>$request['number']],$request->all());
            return response()->json(true, Response::HTTP_OK);
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
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
            return response()->json($project, Response::HTTP_OK);
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
        if(isset(Project::where('uuid','=',$id)->first()['id'])){
            $project=Project::where('uuid','=',$id)->first();
            $pages=Page::where('project_id','=',$project->id)->get();

            $save_path = storage_path('app/'.$project['name'].'.zip');
            $zip = new \ZipArchive();
            $zip->open($save_path, \ZipArchive::CREATE);

            Storage::disk('local'); //公開時はpublicに変更
            Storage::makeDirectory($project['name']);
            Storage::makeDirectory($project['name'].'/css');
            Storage::makeDirectory($project['name'].'/js');
            Storage::makeDirectory($project['name'].'/assets/designs');
            Storage::makeDirectory($project['name'].'/assets/images');
            $setting_json_contents_first="[\n";
            $setting_json_contents_last="]";

            $zip->addEmptyDir('css');
            $zip->addEmptyDir('assets/images');
            try{
                foreach($pages as $page)
                {
                    $response=Http::withToken(config('markdownapi.token'))
                    ->withHeaders([
                        'Accept'=>config('markdownapi.accept'),
                        'Content-type'=>'text/plain'
                    ])
                    ->post(config('markdownapi.url'),[
                        'text'=>$page['contents']
                    ])->throw()->body();
                    $design=Design::find($page['design_id'])->first();
                    Storage::put($project['name'].'/assets/designs/'.$design['name'].'.json', $design['contents']);

                    $file_path = storage_path('app/'. $project['name'].'/assets/designs/'.$design['name'].'.json');
                    $zip->addFile($file_path,'assets/designs/'.$design['name'].'.json');
                    
                    $setting_json_contents[$page['number']]="\t{\n\t\t".'"name":"'.$page['title'].'"'.",\n\t\t".'"design":"'.$design['name'].'"'.",\n\t\t".'"number":'.$page['number']."\n\t},\n";
                    $page['response']= "<html>\n<body>\n<div class=".'"content"'.">\n".$response."</div>\n</body>\n</html>";
                    Storage::put($project['name'].'/'.$page['title'].'_'.$page['number'].'.html', $page['response']);

                    $file_path = storage_path('app/'. $project['name'].'/'.$page['title'].'_'.$page['number'].'.html');
                    $zip->addFile($file_path, $page['title'].'_'.$page['number'].'.html');
                }

                for($i=1;$i<=count($setting_json_contents);$i++)
                {
                    if($i==1)
                    {
                        $content=$setting_json_contents_first.$setting_json_contents[$i];
                    }
                    else if($i==count($setting_json_contents))
                    {
                        $content=$content.$setting_json_contents[$i].$setting_json_contents_last;
                    }
                    else
                    {
                        $content=$content.$setting_json_contents[$i];
                    }
                }

                Storage::put($project['name'].'/assets/settings.json', $content);

                $file_path = storage_path('app/'. $project['name'].'/assets/settings.json');
                $zip->addFile($file_path, 'assets/settings.json');

                $design_setting_content="
export function styleSetter(tags) {
    const root = document.querySelector(':root')
    Object.keys(tags).forEach(tag => {
        Object.keys(tags[tag].attributes).forEach(attribute => {
            root.style.setProperty(\n\t\t\t\t"
                .'`--${tag}-${attribute}`,'
                ."\n\t\t\t\ttags[tag].attributes[attribute].value + tags[tag].attributes[attribute].unit
            )
        })
    })
}";

                Storage::put($project['name'].'/js/design-setting.js', $design_setting_content);

                $file_path = storage_path('app/'. $project['name'].'/js/design-setting.js');
                $zip->addFile($file_path, 'js/design-setting.js');
                
                $zip->close();

                return response()->download($save_path);
                // return response()->download($save_path)->deleteFileAfterSend();
            }
            catch(\GuzzleHttp\Exception\ConnectException $e)
            {
                return response()->json($e->getHandlerContext(), Response::HTTP_BAD_REQUEST);
            }
            catch(\GuzzleHttp\Exception\RequestException $e)
            {
                return response()->json($e->getHandlerContext(), Response::HTTP_BAD_REQUEST);
            }
            catch(Exception $e)
            {
                return response()->json($e, $e->getCode());
            }
        }
        return response()->json(false, Response::HTTP_NOT_FOUND);
    }
}

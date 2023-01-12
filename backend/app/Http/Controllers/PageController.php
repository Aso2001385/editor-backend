<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectDesign;
use App\Models\User;
use App\Models\UserDesign;
use App\Models\Page;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\ProjectCopyRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\PageCollection;
use App\Http\Resources\PageResource;
use App\Http\Resources\PagesResource;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Design;
use Exception;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{


    public function save($uuid,Request $request)
    {

        try {


            if(isset($request->pages)){
                $id = Project::where('uuid',$uuid)->firstOrFail()->id;

                $delete_ids = [];
                $upserts = [];
                $copys = [];
                $copy_ids = [];
                $last = [];

                foreach($request->pages as $index=>$page){

                    if(is_string($page['id'])){

                        $page['id'] = ltrim($page['id'],'C');
                        $copys[] = $page;
                        $copy_ids[] = $index;

                    }else if($page['id'] < 0){

                        $delete_ids[] = $page['id'] * -1;
                    }else if($page['id'] === $request->last){

                        $last = [
                            'id'=>  $page['id'],
                            'user_id' => Auth::id(),
                            'title'=> $page['title'],
                            'contents' => '# 新規ページ',
                            'number' => $page['number'],
                            'project_id' => $id,
                            'design_id' =>  Design::where('uuid',$page['design_uuid'])->firstOrFail()->id
                        ];
                    }else{

                        $upserts[] = [
                            'id'=>  $page['id'],
                            'user_id' => Auth::id(),
                            'title'=> $page['title'],
                            'contents' => '# 新規ページ',
                            'number' => $page['number'],
                            'project_id' => $id,
                            'design_id' =>  Design::where('uuid',$page['design_uuid'])->firstOrFail()->id
                        ];
                    }
                }

                if(count($upserts) - (count($delete_ids)+count($copy_ids)) > 1){
                    array_push($upserts,$last);
                }

                foreach($copys as $copy){
                    $oldrow = Page::find($copy['id']);
                    $newrow = $oldrow->replicate();
                    $newrow['number'] = $copy['number'];
                    $newrow['title'] = $copy['title'];
                    $newrow->save();
                }

                Page::whereIn('id', $delete_ids)->delete();
                logger()->error('aaa');
                logger()->error($upserts);
                Page::upsert($upserts,['id'],['title','number','design_id']);

                return response()->json(new PageCollection(Project::find($id)->pages), Response::HTTP_OK);
            }else{
                $page = Page::find($request->id);
                $page['contents'] = $request->contents;
                $page->save();
                return response()->json(new PageResource($page), Response::HTTP_OK);
            }


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
    public function show($uuid,$number)
    {

        logger()->error([$uuid,$number]);
        try {


            $id = Project::where('uuid',$uuid)->firstOrFail()->id;

            $page = new PageResource(Page::where('project_id',$id)->where('number',$number)->firstOrFail());

            return response()->json($page, Response::HTTP_OK);

        } catch (Exception $e) {
            logger()->error($e);
            return response()->json($e, Response::HTTP_NOT_FOUND);

        }

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
}

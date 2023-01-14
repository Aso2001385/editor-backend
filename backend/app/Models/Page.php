<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Design;
use Illuminate\Support\Facades\Auth;

class Page extends Model
{
    use HasFactory;
    protected $datas = ['deleted_at'];

    protected $fillable = [
        'project_id',
        'number',
        'user_id',
        'design_id',
        'title',
        'contents'
    ];


    public static function firstAdd($project_id,$design_id=null){

        $page = Page::create([
            'project_id'=>$project_id,
            'user_id'=>Auth::id(),
            'design_id'=>$design_id ?? ProjectDesign::where('project_id',$project_id)->first()->design_id,
            'number'=>1,
            'title'=>'新規ページ',
            'contents'=>'# 新規ページ',
        ]);
        return $page;
    }

    public static function last($pages,$project_id){

        $page = $pages->sortByDesc('updated_at')->first();
        if($page){
            logger()->error('if');
            logger()->error($page);
        }else{
            logger()->error('else');
            logger()->error($page = self::firstAdd($project_id));
        }

        return [
            'number' => $page->number,
            'contents' => $page->contents,
            'updated_at' => $page->updated_at
        ];

    }

}

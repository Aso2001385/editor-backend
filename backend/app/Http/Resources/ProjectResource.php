<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Page;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $last_page = $this->pages->sortByDesc('updated_at')->first();

        return [
            'uuid'=>$this->uuid,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'ui' => $this->ui,
            'count'=>count(Page::where('project_id','=',$this->id)->get()),
            'last'=> [
                'number' => $last_page->number,
                'contents' => $last_page->contents,
                'updated_at' => $last_page->updated_at
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pages' => new PageCollection($this->pages),
        ];
    }
}

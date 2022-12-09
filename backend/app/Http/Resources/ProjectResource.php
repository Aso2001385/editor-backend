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
        return [
            'uuid'=>$this->uuid,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'ui' => $this->ui,
            'count'=>count(Page::where('project_id','=',$this->id)->get()),
            'last_update'=>Page::where('project_id','=',$this->id)->max('updated_at'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pages' => new PageCollection($this->pages),
        ];
    }
}

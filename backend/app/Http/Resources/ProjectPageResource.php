<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Page;

class ProjectPageResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_update_pages' =>Page::where('updated_at','=',Page::where('project_id','=',$this->id)->max('updated_at'))->where('project_id','=',$this->id)->get(),
        ];
    }
}

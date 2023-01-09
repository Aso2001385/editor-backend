<?php

namespace App\Http\Resources;

use App\Models\Design;
use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'id' => $this->id,
            'project_uuid' => Project::find($this->project_id)->uuid,
            'number' => $this->number,
            'user_id' => $this->user_id,
            'design_uuid' => Design::find($this->design_id)->uuid,
            'title' => $this->title,
            'contents' => $this->contents,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

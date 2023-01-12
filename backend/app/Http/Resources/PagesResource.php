<?php

namespace App\Http\Resources;

use App\Models\Design;
use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class PagesResource extends JsonResource
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
            'design_uuid' => Design::find($this->design_id)->uuid,
            'number' => $this->number,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

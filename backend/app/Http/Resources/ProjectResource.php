<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Page;

class ProjectResource extends JsonResource
{

    public function toArray($request)
    {
        logger()->error( Page::last($this->pages,$this->id));

        return [
            'uuid'=>$this->uuid,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'ui' => $this->ui,
            'count' => count(Page::where('project_id','=',$this->id)->get()),
            'pages' => PagesResource::collection($this->pages),
            'last' => Page::last($this->pages,$this->id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

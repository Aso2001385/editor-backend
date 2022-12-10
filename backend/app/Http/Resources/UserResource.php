<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Project;
use App\Models\Design;
use App\Models\UserDesign;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'point' => $this->point,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'projects' => new ProjectCollection($this->projects),
            'designs' => new DesignCollection($this->designs)
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
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
            'uuid' => $this->uuid,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'point' => $this->point,
            'contents' => $this->contents,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

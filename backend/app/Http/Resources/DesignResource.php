<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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

        try{
            $preview = Storage::get('previews/designs/'.$this->uuid.'.txt');
        }catch(Exception $e){
            $preview = null;
        }

        return [
            'uuid' => $this->uuid,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'point' => $this->point,
            'contents' => $this->contents,
            'preview' => $preview,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

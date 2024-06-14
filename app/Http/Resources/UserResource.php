<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            "id"=> $this->id,
            "name"=> $this->name,
            "email"=> $this->email,
            "image"=> $this->image,
            "gender"=> $this->gender,
            "age"=> $this->age,
            "phone"=> $this->phone,
            "longitude"=> $this->longitude,
            "latitude"=> $this->latitude,
            "images"=>UserImageResource::collection($this->images),

        ];
    }
}

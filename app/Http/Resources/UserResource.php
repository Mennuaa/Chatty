<?php

namespace App\Http\Resources;

use App\Models\Story;
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
            "avatar"=> $this->image,
            "gender"=> $this->gender,
            "age"=> $this->age,
            "phone"=> $this->phone,
            "longitude"=> $this->longitude,
            "latitude"=> $this->latitude,
            "description"=> $this->description,
            "stories"=> Story::where('user_id', $this->id)->get(),
            "images"=>UserImageResource::collection($this->images),

        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
            'post_title' => $this->title ,
            'post_content' => $this->content ,
            'post_category' => CategoryResource::make($this->whenLoaded('category')),
            'created_Post' => UserResource::make($this->whenLoaded('user'))
        ];    
    }
}

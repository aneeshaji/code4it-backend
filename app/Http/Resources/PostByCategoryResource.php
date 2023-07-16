<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostByCategoryResource extends JsonResource
{
    /**
     * Resource For Posts By Category API.
     * Created On : 28-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'categories' => $this->categories,
            'image' => asset('uploads/posts/' . $this->image),
            'created_at' => $this->created_at
        ];
    }
}
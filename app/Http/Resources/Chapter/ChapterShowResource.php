<?php

namespace App\Http\Resources\Chapter;

use Illuminate\Http\Resources\Json\JsonResource;

class ChapterShowResource extends JsonResource
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
            'slug'              => $this->slug,
            'title'             => $this->title,
            'content'           => $this->content,
            'date_release'      => $this->date_release,
            'rating'            => $this->rating,
            'total_reviews'     => $this->total_reviews,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,

            'user'              => $this->user,
            'book'              => $this->book,
            'reviews'           => $this->reviews
        ];
    }
}

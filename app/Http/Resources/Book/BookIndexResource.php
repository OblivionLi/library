<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Resources\Json\JsonResource;

class BookIndexResource extends JsonResource
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
            'author'            => $this->author,
            'translator'        => $this->translator,
            'status'            => $this->status,
            'description'       => $this->description,
            'date_release'      => $this->date_release,
            'cover'             => $this->cover,
            'rating'            => $this->rating,
            'total_reviews'     => $this->total_reviews,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,

            'user'              => $this->user,
            'chapters'          => $this->chapters,
            'genres'            => $this->genres,
            'reviews'           => $this->reviews
        ];
    }
}

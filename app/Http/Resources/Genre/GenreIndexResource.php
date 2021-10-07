<?php

namespace App\Http\Resources\Genre;

use Illuminate\Http\Resources\Json\JsonResource;

class GenreIndexResource extends JsonResource
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
            'name'          => $this->name,
            'books'         => $this->books,

            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

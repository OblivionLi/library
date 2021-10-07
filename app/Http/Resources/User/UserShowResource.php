<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserShowResource extends JsonResource
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
            'name'              => $this->name,
            'email'             => $this->email,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,

            'books'             => $this->books,
            'bookReviews'       => $this->bookReviews,
            'chapterReviews'    => $this->chapterReviews,
            'chapters'          => $this->chapters,
            'roles'             => $this->roles->pluck('name'),
        ];
    }
}

<?php

namespace App\Http\Resources\ReviewChapter;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewChapterIndexResource extends JsonResource
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
            'user_name'     => $this->user_name,
            'user_comment'  => $this->user_comment,
            'admin_name'    => $this->admin_name,
            'admin_comment' => $this->admin_comment,
            'rating'        => $this->rating,

            'chapter'       => $this->chapter,
            'user'          => $this->user
        ];
    }
}

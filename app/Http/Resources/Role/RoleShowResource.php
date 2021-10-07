<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleShowResource extends JsonResource
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
            'is_admin'      => $this->is_admin,
            'users'         => $this->users->pluck('name'),
            'permissions'   => $this->permissions->pluck('name'),

            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources\Auth\Login;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    protected $token;

    public function __construct($resource, $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'message'       => 'User login success',
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'role'          => $this->roles->pluck('name'),
            'is_admin'      => $this->roles->pluck('is_admin'),
            'access_token'  => $this->token,
        ];
    }
}

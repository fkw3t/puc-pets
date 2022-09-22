<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'list all users' => '/api/auth/user',
                'search for specific user' => '/api/auth/user/{id}',
                'search for specific user by document' => '/api/auth/user/document/{document}',
                'create user' => '/api/user/store',
            ],
        ];
    }
}

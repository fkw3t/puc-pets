<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Expense\ExpenseResource;
use App\Http\Resources\Pet\PetResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'document' => $this->document_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'pets' => $this->pets
        ];
    }
}

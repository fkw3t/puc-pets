<?php

namespace App\Http\Resources\Schedule;

use App\Http\Resources\Pet\PetResource;
use App\Http\Resources\Vet\VetResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'status' => $this->status, 
            'date' => $this->date, 
            'vet' => new VetResource($this->whenLoaded('vet')),
            'pet' => new PetResource($this->whenLoaded('pet')),
            'client' => new UserResource($this->whenLoaded('client'))
        ];
    }
}

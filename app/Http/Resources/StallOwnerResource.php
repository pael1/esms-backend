<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\StallOwnerChildResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StallOwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stallOwnerId' => $this->stallOwnerId,
            'ownerId' => $this->ownerId,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'midinit' => $this->midinit,
            'full_name' => $this->firstname . ' ' . $this->midinit . '. ' . $this->lastname,
            'civilStatus' => $this->civilStatus,
            'address' => $this->address,
            'spouseLastname' => $this->spouseLastname,
            'spouseFirstname' => $this->spouseFirstname,
            'spouseMidint' => $this->spouseMidint,
            'ownerStatus' => $this->ownerStatus,
            'attachIdPhoto' => $this->attachIdPhoto,
            'dateRegister' => $this->dateRegister,
            'contactnumber' => $this->contactnumber,
            'childrens' => StallOwnerChildResource::collection($this->whenLoaded('childrens')),
            'ledger' => StallOwnerAccountResource::collection($this->whenLoaded('ledger'))
        ];
    }
}

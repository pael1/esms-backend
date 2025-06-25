<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AwardeeDetailsResource extends JsonResource
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
            'full_name' => $this->full_name,
            'civilStatus' => $this->civilStatus,
            'address' => $this->address,
            'spouseLastname' => $this->spouseLastname,
            'spouseFirstname' => $this->spouseFirstname,
            'spouseMidint' => $this->spouseMidint,
            'spouse_full_name' => $this->spouse_full_name,
            'ownerStatus' => $this->ownerStatus,
            'attachIdPhoto' => $this->attachIdPhoto,
            'dateRegister' => $this->dateRegister,
            'contactnumber' => $this->contactnumber,
            'leaseContract' => $this->leaseContract,
            'stallDescription' => $this->stallDescription,
            'stallNoId' => $this->stallNoId,
            'rate_per_month' => number_format($this->rate_per_month, 2),
            'stallRentalDet' => new StallRentalDetResource($this->whenLoaded('stallRentalDet'))
        ];
    }
}

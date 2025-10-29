<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
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
            'full_name' => $this->firstname.' '.$this->midinit.'. '.$this->lastname,
            'civilStatus' => $this->civilStatus,
            'address' => $this->address,
            'spouseLastname' => $this->spouseLastname,
            'spouseFirstname' => $this->spouseFirstname,
            'spouseMidint' => $this->spouseMidint,
            'ownerStatus' => $this->ownerStatus,
            'attachIdPhoto' => $this->attachIdPhoto,
            'dateRegister' => $this->dateRegister,
            'contactnumber' => $this->contactnumber,
            'rental_status' => $this->rental_status,
            'childrens' => StallOwnerChildResource::collection(
                $this->whenLoaded('childrens', $this->childrens, collect())
            ),
            'employees' => StallOwnerEmpResource::collection(
                $this->whenLoaded('employees', $this->employees, collect())
            ),
            'files' => StallOwnerFilesResource::collection(
                $this->whenLoaded('files', $this->files, collect())
            ),
            'ledger' => StallOwnerAccountResource::collection(
                $this->whenLoaded('ledger', $this->ledger, collect())
            ),
            'stallRentalDet' => StallRentalDetResource::make(
                $this->whenLoaded('stallRentalDet', $this->stallRentalDet)
            ),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallOwnerEmpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stallOwnerEmpId' => $this->stallOwnerEmpId,
            'ownerId' => $this->ownerId,
            'employeeName' => $this->employeeName,
            'dateOfBirth' => $this->dateOfBirth,
            'STALLOWNER_stallOwnerId' => $this->STALLOWNER_stallOwnerId,
            'age' => $this->age,
            'address' => $this->address,
        ];
    }
}

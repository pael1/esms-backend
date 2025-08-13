<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallRentalDetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stallDetailId' => $this->stallDetailId,
            'ownerId' => $this->ownerId,
            'stallNo' => $this->stallNo,
            'rentalStatus' => $this->rentalStatus,
            'contractStartDate' => $this->contractStartDate,
            'contractYear' => $this->contractYear,
            'contractEndDate' => $this->contractEndDate,
            'busID' => $this->busID,
            'busPlateNo' => $this->busPlateNo,
            'busDateStart' => $this->busDateStart,
            'capital' => $this->capital,
            'lineOfBusiness' => $this->lineOfBusiness,
            'STALLOWNER_stallOwnerId' => $this->STALLOWNER_stallOwnerId,
            'leaseContract' => $this->leaseContract,
            'documentFiles' => $this->documentFiles,
            'busIDStatus' => $this->busIDStatus,
            'stallProfile' => new StallProfileResource($this->whenLoaded('stallProfile')),
            'stallOwner' => StallOwnerResource::make($this->whenLoaded('stallOwner')),
            // 'stallProfileViews' => new StallProfileViewsResource($this->whenLoaded('stallProfileViews')),
        ];
    }
}

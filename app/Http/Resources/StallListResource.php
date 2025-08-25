<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\StallOwnerResource;
use App\Http\Resources\StallRentalDetResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StallListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $awardee = null;

        if ($this->stallRental && $this->stallRental->stallOwner) {
            $awardee = trim(
                $this->stallRental->stallOwner->firstname . ' ' .
                $this->stallRental->stallOwner->midinit . '. ' .
                $this->stallRental->stallOwner->lastname
            );
        }

        return [
            'stallProfileId' => $this->stallProfileId,
            'stallDescription' => $this->stallDescription,
            'stallNo' => $this->stallNo,
            'stallNoId' => $this->stallNoId,
            'stallArea' => $this->stallArea,
            'stallAreaExt' => $this->stallAreaExt,
            'CFSI' => $this->CFSI,
            'ratePerDay' => $this->ratePerDay,
            'awardee' => $awardee,
            'status' => $this->stallStatus,
            'stallRental' => StallRentalDetResource::make($this->whenLoaded('stallRental')),
        ];
    }
}

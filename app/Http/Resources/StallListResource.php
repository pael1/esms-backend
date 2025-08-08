<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
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
        return [
            'stallProfileId' => $this->stallProfileId,
            'stallDescription' => $this->stallDescription,
            'stallNo' => $this->stallNo,
            'stallArea' => $this->stallArea,
            'stallAreaExt' => $this->stallAreaExt,
            'CFSI' => $this->CFSI,
            'ratePerDay' => $this->ratePerDay,
            'awardee' => $this->firstname.' '.$this->midinit.'. '.$this->lastname,
            'status' => $this->rentalStatus,
        ];
    }
}

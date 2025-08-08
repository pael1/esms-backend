<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'stallrateid' => $this->stallrateid,
            'AppliedYear' => $this->AppliedYear,
            'SectionCode' => $this->SectionCode,
            'StallType' => $this->StallType,
            'StallClass' => $this->StallClass,
            'Rates' => $this->Rates,
        ];
    }
}

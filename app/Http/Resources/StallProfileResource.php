<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallProfileResource extends JsonResource
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
            'stallNo' => $this->stallNo,
            'stallType' => $this->stallType,
            'sectionCode' => $this->sectionCode,
            'marketCode' => $this->marketCode,
            'stallDescription' => $this->stallDescription,
            'picPath' => $this->picPath,
            'stallArea' => $this->stallArea,
            'stallClass' => $this->stallClass,
            'CFSI' => $this->CFSI,
            'stallStatus' => $this->stallStatus,
            'fixRatePerSqm' => $this->fixRatePerSqm,
            'ratePerDay' => $this->ratePerDay,
            'ratePerMonth' => $this->ratePerMonth,
            'stallNoId' => $this->stallNoId,
            'StallAreaExt' => $this->StallAreaExt,

            // Accessors from the Stallprofile model
            'stallRate' => $this->stallRate,
            'locationInfluenceRate' => $this->locationInfluenceRate,
            'Total_InfluenceRate' => $this->Total_InfluenceRate,
            'baseRates' => $this->baseRates,
            'extensionRate' => $this->extensionRate,
            'Total_extensionRate' => $this->Total_extensionRate,
            'Total_baseRate' => $this->Total_baseRate,
            'TotalRatePerDay' => $this->TotalRatePerDay,

            'signatory' => new SignatoryResource($this->whenLoaded('signatory')),
            'officecode' => new OfficeCodeResource($this->whenLoaded('officecode')),
        ];
    }
}

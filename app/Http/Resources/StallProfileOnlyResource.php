<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallProfileOnlyResource extends JsonResource
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
            'section_id' => $this->section_id,
            'sub_section_id' => $this->sub_section_id,
            'building_id' => $this->building_id,
            'stall_id_ext' => $this->stall_id_ext,
            'stall_no_id' => $this->stall_no_id,
        ];
    }
}

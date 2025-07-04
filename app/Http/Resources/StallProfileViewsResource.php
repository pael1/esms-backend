<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallProfileViewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stallNo' => $this->stallNo,
            'stallArea' => $this->stallArea,
            'stallRate' => $this->stallRate,
            'locationInfluenceRate' => $this->locationInfluenceRate,
            'Total_InfluenceRate' => $this->Total_InfluenceRate,
            'baseRates' => $this->baseRates,
            'extensionRate' => $this->extensionRate,
            'stallAreaExt' => $this->stallAreaExt,
            'Total_extensionRate' => $this->Total_extensionRate,
            'Total_baseRate' => $this->Total_baseRate,
            'RatePerDay' => $this->RatePerDay,
            'total_amount' => $this->total_amount,
            'extension' => $this->extension,
            'account_codes' => $this->account_codes,
        ];
    }
}

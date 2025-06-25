<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficeCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'officecodeid ' => $this->officecodeid,
            'officeCode' => $this->officeCode,
            'marketOfficeCode' => $this->marketOfficeCode,
            'officeName' => $this->officeName,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignatoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'signatoryId' => $this->signatoryId,
            'signatoryFullName' => $this->signatoryFullName,
            'signatorydesignation' => $this->signatorydesignation,
            'marketOfficeCode' => $this->marketOfficeCode,
            'status' => $this->status,
            'encodedby' => $this->encodedby,
        ];
    }
}

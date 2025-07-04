<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParameterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'parameterId' => $this->parameterId,
            'fieldId' => $this->fieldId,
            'fieldValue' => $this->fieldValue,
            'fieldDescription' => $this->fieldDescription,
            'dateTimeEncoded' => $this->dateTimeEncoded,
            'encodedBy' => $this->encodedBy,
        ];
    }
}

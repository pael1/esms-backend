<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallOwnerChildResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stallOwnerChildId' => $this->stallOwnerChildId,
            'ownerId' => $this->ownerId,
            'childName' => $this->childName,
            'childBDate' => $this->childBDate,
            'STALLOWNER_stallOwnerId' => $this->STALLOWNER_stallOwnerId,
        ];
    }
}

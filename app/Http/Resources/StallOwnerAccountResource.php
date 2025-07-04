<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallOwnerAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stallOwnerAccountId' => $this->stallOwnerAccountId,
            'ownerId' => $this->ownerId,
            'month' => $this->month,
            'year' => $this->year,
            'date' => $this->month.' '.$this->year,
            'amountBasic' => $this->amountBasic,
            'amountSurc' => $this->amountSurc,
            'amountInt' => $this->amountInt,
            'ORNum' => $this->ORNum,
            'OPRefId' => $this->OPRefId,
            'ORDate' => $this->ORDate,
            'generatedBy' => $this->generatedBy,
            'isadded' => $this->isadded,
            'expdate' => $this->expdate,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'EmpId' => $this->EmpId,
            'UserLastName' => $this->UserLastName,
            'UserFirstName' => $this->UserFirstName,
            'UserMidInit' => $this->UserMidInit,
            'OfficeCode' => $this->OfficeCode,
            'PositionTitle' => $this->PositionTitle,
            'ItemNo' => $this->ItemNo,
            'Designation' => $this->Designation,
            'DateTimeCreated' => $this->DateTimeCreated,
            'CreatedBy' => $this->CreatedBy,
            'MarketCode' => $this->MarketCode,
        ];
    }
}

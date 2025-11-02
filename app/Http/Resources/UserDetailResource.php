<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'employee_id'   => $this->employee_id,
            'firstname'     => $this->firstname,
            'midinit'       => $this->midinit,
            'lastname'      => $this->lastname,
            'office'        => $this->office,
            'office_name'   => trim("{$this->officeCode?->officeName}"),
            'position'      => $this->position,
            'name'          => trim("{$this->firstname} {$this->midinit} {$this->lastname}"),
        ];
    }
}

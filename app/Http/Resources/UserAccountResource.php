<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'UserId' => $this->UserId,
            'Password' => $this->Password,
            'DateTimeCreated' => $this->DateTimeCreated,
            'CreatedBy' => $this->CreatedBy,
            'NextReset' => $this->NextReset,
            'LastReset' => $this->LastReset,
            'ResetBy' => $this->ResetBy,
            'AccountGroup' => $this->AccountGroup,
            'DefWorkDayPermit' => $this->DefWorkDayPermit,
            'DefWorkTimePermitStart' => $this->DefWorkTimePermitStart,
            'DefWorkTimePermitEnd' => $this->DefWorkTimePermitEnd,
            'SysAdmin' => $this->SysAdmin,
            'Supervisor' => $this->Supervisor,
            'DistCode' => $this->DistCode,
            'AccntStat' => $this->AccntStat,
            'AccntStatDate' => $this->AccntStatDate,
            'LastLogIn' => $this->LastLogIn,
            'LastLogOut' => $this->LastLogOut,
            'LastLogIPAddress' => $this->LastLogIPAddress,
            'SystemUser_EmpId' => $this->SystemUser_EmpId,
            'user_detail' => new SystemUserResource($this->whenLoaded('user_detail')),
        ];
    }
}

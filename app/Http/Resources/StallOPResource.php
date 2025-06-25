<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallOPResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'OPRefId' => $this->OPRefId,
            'opDate' => $this->opDate,
            'accountCode' => $this->accountCode,
            'amountBasic' => $this->amountBasic,
            'amountInt' => $this->amountInt,
            'amountSurc' => $this->amountSurc,
            'postDateTime' => $this->postDateTime,
            'postBy' => $this->postBy,
            'ORNum' => $this->ORNum,
            'ORDate' => $this->ORDate,
            'ORNumExt' => $this->ORNumExt,
            'purpose' => $this->purpose,
        ];
    }
}

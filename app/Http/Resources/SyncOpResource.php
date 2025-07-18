<?php

namespace App\Http\Resources;

use App\Models\StallOwnerAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SyncOpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ornumber' => $this->ornumber,
            'ownerid' => $this->ownerid,
            'is_processed' => $this->is_processed,
            'months' => $this->months,
            'month_names' => $this->getMonthNames(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getMonthNames()
    {
        if (!is_array($this->months)) {
            return [];
        }

        return StallOwnerAccount::whereIn('stallOwnerAccountId', $this->months)
                ->get()
                ->map(fn($m) => "{$m->month} {$m->year}")
                ->toArray();
    }
}

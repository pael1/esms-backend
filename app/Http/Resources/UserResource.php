<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username'      => $this->username,
            'is_admin'      => (bool) $this->is_admin,
            'is_supervisor' => (bool) $this->is_supervisor,
            'status' => (bool) $this->status,
            'created_at'    => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),

            // include related details
            'details'       => new UserDetailResource($this->whenLoaded('details')),
        ];
    }
}

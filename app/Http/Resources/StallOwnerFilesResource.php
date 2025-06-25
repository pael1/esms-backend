<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StallOwnerFilesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stallOwnerFileId' => $this->stallOwnerFileId,
            'ownerId' => $this->ownerId,
            'attachFileType' => $this->attachFileType,
            'filePath' => $this->filePath,
        ];
    }
}

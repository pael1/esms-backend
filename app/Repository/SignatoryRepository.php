<?php

namespace App\Repository;

use App\Interface\Repository\SignatoryRepositoryInterface;
use App\Models\Signatory;

class SignatoryRepository implements SignatoryRepositoryInterface
{
    public function findById(string $id)
    {
        $signatory = Signatory::where('signatoryId', $id)->first();
        return $signatory;
    }
}

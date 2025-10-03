<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Http\Resources\StallOwnerFilesResource;
use App\Http\Resources\StallOwnerResource;
use App\Interface\Service\StallOwnerServiceInterface;
use App\Interface\Repository\StallOwnerRepositoryInterface;

class StallOwnerService implements StallOwnerServiceInterface
{
    private $StallOwnerRepository;

    public function __construct(StallOwnerRepositoryInterface $StallOwnerRepository)
    {
        $this->StallOwnerRepository = $StallOwnerRepository;
    }

    public function findMany(object $payload)
    {
        $stallOwner = $this->StallOwnerRepository->findMany($payload);

        return StallOwnerResource::collection($stallOwner);
    }

    public function create(array $payload)
    {
        $stallOwner = $this->StallOwnerRepository->create($payload);

        return $stallOwner;
    }

    public function show(string $id) {}

    public function update(array $payload, string $id)
    {
        return $this->StallOwnerRepository->update($payload, $id);
    }

    public function delete(string $id)
    {
        return $this->StallOwnerRepository->delete($id);
    }
}

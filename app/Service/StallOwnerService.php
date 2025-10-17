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

    public function findOwner(string $ownerId)
    {
        $stallOwner = $this->StallOwnerRepository->findOwner($ownerId);

        return StallOwnerResource::make($stallOwner);
    }

    public function create(array $payload)
    {
        $stallOwner = $this->StallOwnerRepository->create($payload);
        return StallOwnerResource::make($stallOwner);
    }

    public function show(string $id) {}

    public function update(array $payload, string $id)
    {
        $stallOwner = $this->StallOwnerRepository->update($payload, $id);
        return StallOwnerResource::make($stallOwner);
    }

    public function delete(string $id)
    {
        return $this->StallOwnerRepository->delete($id);
    }
}

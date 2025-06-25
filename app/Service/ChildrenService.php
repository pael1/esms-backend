<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Http\Resources\StallOwnerChildResource;
use App\Http\Resources\StallOwnerAccountResource;
use App\Interface\Service\LedgerServiceInterface;
use App\Interface\Service\ChildrenServiceInterface;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Repository\ChildrenRepositoryInterface;

class ChildrenService implements ChildrenServiceInterface
{
    private $childrenRepository;

    public function __construct(ChildrenRepositoryInterface $childrenRepository)
    {
        $this->childrenRepository = $childrenRepository;
    }

    public function findMany(object $payload)
    {
        $childrens = $this->childrenRepository->findMany($payload);
        return StallOwnerChildResource::collection($childrens);
    }

    public function create(object $payload)
    {
        $user = $this->childrenRepository->create($payload);

        return new UserResource($user);
    }

    public function show(string $id) {}

    public function update(object $payload, string $id)
    {
        $user = $this->childrenRepository->update($payload, $id);

        return new UserResource($user);
    }

    public function delete(string $id)
    {
        return $this->childrenRepository->delete($id);
    }
}

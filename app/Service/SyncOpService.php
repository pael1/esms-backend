<?php

namespace App\Service;

use App\Http\Resources\StallOPResource;
use App\Http\Resources\StallOwnerAccountResource;
use App\Http\Resources\SyncOpResource;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Repository\SyncOpRepositoryInterface;
use App\Interface\Service\SyncOpServiceInterface;
use App\Models\StallOwnerAccount;

class SyncOpService implements SyncOpServiceInterface
{
    private $syncOpRepository;
    private $ledgerRepository;

    public function __construct(SyncOpRepositoryInterface $syncOpRepository, LedgerRepositoryInterface $ledgerRepository)
    {
        $this->syncOpRepository = $syncOpRepository;
        $this->ledgerRepository = $ledgerRepository;
    }

    public function findMany(object $payload)
    {
        $data = $this->syncOpRepository->findMany($payload);
        return SyncOpResource::collection($data);
    }

    public function findArrears(object $payload)
    {
        $data = $this->syncOpRepository->findArrears($payload);
        return StallOwnerAccountResource::collection($data);   
    }

    public function findManyById(string $id)
    {
        $data = $this->syncOpRepository->findManyById($id);

        return SyncOpResource::collection($data);
    }

    public function findById(string $id)
    {
        // $op = $this->opRepository->findByFieldIdFieldValue($id);

        // return ParameterResource::collection($op); // for multiple data
        // return new ParameterResource($op); //for only 1 data
    }

    public function create(array $payload)
    {
        $data = $this->syncOpRepository->create($payload);

        //update the stallowner account for the sync operation
        $this->ledgerRepository->updateSync($payload);

        return new SyncOpResource($data);
    }

    public function update(object $payload, string $id)
    {
        // $user = $this->userRepository->update($payload, $id);

        // return new UserResource($user);
    }

    public function delete(string $id)
    {
        // return $this->userRepository->delete($id);
    }
}

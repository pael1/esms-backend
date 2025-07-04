<?php

namespace App\Service;

use App\Http\Resources\StallOwnerAccountResource;
use App\Http\Resources\UserResource;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Service\LedgerServiceInterface;

class LedgerService implements LedgerServiceInterface
{
    private $ledgerRepository;

    public function __construct(LedgerRepositoryInterface $ledgerRepository)
    {
        $this->ledgerRepository = $ledgerRepository;
    }

    public function findManyLedger(object $payload)
    {
        $ledger = $this->ledgerRepository->findManyLedger($payload);

        return StallOwnerAccountResource::collection($ledger);
    }

    public function findManyLedgerArrears(object $payload)
    {
        $ledger = $this->ledgerRepository->findManyLedgerArrears($payload);

        return StallOwnerAccountResource::collection($ledger);
    }

    public function createLedger(object $payload)
    {
        $user = $this->ledgerRepository->create($payload);

        return new UserResource($user);
    }

    public function updateLedger(object $payload, string $id)
    {
        $user = $this->ledgerRepository->update($payload, $id);

        return new UserResource($user);
    }

    public function deleteLedger(string $id)
    {
        return $this->ledgerRepository->delete($id);
    }
}

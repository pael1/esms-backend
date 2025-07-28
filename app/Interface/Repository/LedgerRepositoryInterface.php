<?php

namespace App\Interface\Repository;

interface LedgerRepositoryInterface
{
    public function findManyLedger(object $payload);

    public function findManyLedgerArrears(object $payload);

    public function createLedger(object $payload);

    public function updateLedger(object $payload, string $id);

    public function deleteLedger(string $id);

    public function updateSync(array $payload);
    
    public function updateLedgerSync(array $payload);

}

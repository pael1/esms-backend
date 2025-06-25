<?php

namespace App\Interface\Service;

interface LedgerServiceInterface
{
    public function findManyLedger(object $payload);

    public function createLedger(object $payload);

    public function updateLedger(object $payload, string $id);

    public function deleteLedger(string $id);
}

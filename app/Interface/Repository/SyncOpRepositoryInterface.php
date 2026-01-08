<?php

namespace App\Interface\Repository;

interface SyncOpRepositoryInterface
{
    public function findMany(object $payload);

    public function findArrears(object $payload);

    public function findManyById(string $id);

    public function findById(string $id);

    public function updatePaidManuallyById(object $payload, string $id, string $status);
    
    public function updateById(string $id, string $status);
    
    public function create(object $payload);

    public function update(object $payload, string $id);

    public function delete(string $id);
}

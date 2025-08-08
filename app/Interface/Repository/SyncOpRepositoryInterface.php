<?php

namespace App\Interface\Repository;

interface SyncOpRepositoryInterface
{
    public function findMany(object $payload);

    public function findArrears(object $payload);

    public function findManyById(string $id);

    public function findById(string $id);

    public function updateById(string $id);
    
    public function create(object $payload);

    public function update(object $payload, string $id);

    public function delete(string $id);
}

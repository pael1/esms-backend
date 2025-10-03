<?php

namespace App\Interface\Repository;

interface StallOwnerRepositoryInterface
{
    public function findMany(object $payload);

    public function create(array $payload);

    public function show(string $id);

    public function update(array $payload, string $id);

    public function delete(string $id);
}

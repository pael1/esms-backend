<?php

namespace App\Interface\Service;

interface StallOwnerServiceInterface
{
    public function findMany(object $payload);

    public function findOwner(string $ownerId);

    public function create(array $payload);

    public function show(string $id);

    public function update(array $payload, string $id);

    public function delete(string $id);
}

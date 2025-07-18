<?php

namespace App\Interface\Repository;

interface SignatoryRepositoryInterface
{
    // public function findMany(object $payload);

    public function findById(string $id);

    // public function update(object $payload, string $id);
}

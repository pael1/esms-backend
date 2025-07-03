<?php

namespace App\Interface\Service;

interface OpServiceInterface
{
    public function findMany(object $payload);

    public function findById(string $id);

    public function create(object $payload);

    public function update(object $payload, string $id);

    public function delete(string $id);
}

<?php

namespace App\Interface\Repository;

interface OpRepositoryInterface
{
    public function findMany(object $payload);

    public function findById(string $id);

    public function OPDueDate(bool $due = false);

    public function checkOP(object $payload);

    public function saveOP(object $payload);

    public function create(object $payload);

    public function update(object $payload, string $id);

    public function delete(string $id);
}

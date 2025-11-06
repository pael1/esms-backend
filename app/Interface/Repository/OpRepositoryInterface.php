<?php

namespace App\Interface\Repository;

interface OpRepositoryInterface
{
    public function findMany(object $payload);

    public function findById(string $id);

    public function OPDueDate(bool $due = false);

    public function checkOP(object $payload, array $items);

    public function saveOP(object $payload, string $ids);

    public function create(object $payload);

    public function update(object $payload, string $id);

    public function getAccountCode(string $office_code, string $description, string $description1);

    public function delete(string $id);
}

<?php

namespace App\Interface\Repository;

interface FileRepositoryInterface
{
    public function findMany(object $payload);

    public function create(object $payload);

    public function show(string $id);

    public function update(object $payload, string $id);

    public function delete(string $id);
}

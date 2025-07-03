<?php

namespace App\Interface\Repository;

interface ParameterRepositoryInterface
{

    public function findMany(object $payload);

    public function findByFieldId(string $id);

    public function create(object $payload);

    public function update(object $payload, string $id);

    public function delete(string $id);
}

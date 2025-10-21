<?php
namespace App\Interface\Repository;

interface RentalRepositoryInterface
{
    public function findMany(object $payload);

    public function findById(string $id);

    public function create(array $payload);

    public function update(string $id, array $payload);

    public function delete(string $id);
}
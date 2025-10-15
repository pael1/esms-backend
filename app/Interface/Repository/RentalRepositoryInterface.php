<?php
namespace App\Interface\Repository;

interface RentalRepositoryInterface
{
    public function findMany(object $payload);

    public function findById(string $id);

    public function create(object $payload);

    public function update(string $id, object $payload);
    
    public function delete(string $id);
}
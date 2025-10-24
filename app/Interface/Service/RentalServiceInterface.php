<?php

namespace App\Interface\Service;

interface RentalServiceInterface
{
    public function findMany(object $payload);

    public function findById(string $id);

    public function create(array $payload);

    public function update(string $stallId, array $payload);

    public function delete(string $id);

    public function cancelRental(string $id);
}
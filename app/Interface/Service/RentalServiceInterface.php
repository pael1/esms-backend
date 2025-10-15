<?php

namespace App\Interface\Service;

interface RentalServiceInterface
{
    public function findMany(object $payload);

    public function findById(string $id);

    public function create(object $payload);

    public function update(string $stallId, object $payload);

    public function delete(string $id);
}
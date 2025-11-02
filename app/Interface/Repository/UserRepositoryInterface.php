<?php

namespace App\Interface\Repository;

interface UserRepositoryInterface
{
    public function findMany(object $payload);

    public function findById(string $UserId);

    // public function findByOwnerId(string $AwardeeId);

    public function findByUsername(string $email);

    public function create(array $payload);

    public function update(array $payload, string $id);

    public function findOffices();

    public function delete(string $id);
}

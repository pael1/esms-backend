<?php

namespace App\Interface\Repository;

interface UserRepositoryInterface
{
    public function findMany(object $payload);

    public function findById(string $UserId);

    // public function findByOwnerId(string $AwardeeId);

    public function findByUsername(string $email);

    public function create(object $payload);

    public function update(object $payload, string $id);

    public function delete(string $id);
}

<?php

namespace App\Interface\Service;

interface UserServiceInterface
{
    public function findManyUsers(object $payload);

    // public function findAwardeeById(string $id);

    public function findUserByEmail(string $email);

    public function createUser(object $payload);

    public function updateUser(object $payload, string $id);

    public function deleteUser(string $id);
}

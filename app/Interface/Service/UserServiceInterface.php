<?php

namespace App\Interface\Service;

interface UserServiceInterface
{
    public function findManyUsers(object $payload);

    public function findUserById(string $id);

    // public function findUserByEmail(string $email);

    public function createUser(array $payload);

    public function updateUser(array $payload, string $id);

    public function findOffices();

    public function deleteUser(string $id);
}

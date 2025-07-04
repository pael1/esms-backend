<?php

namespace App\Interface\Service;

interface AwardeeServiceInterface
{
    public function findManyAwardee(object $payload);

    // public function findManyLedger(object $payload);

    public function find_many_childrens(object $payload);

    public function find_many_transactions(object $payload);

    public function find_many_files(object $payload);

    public function find_many_employees_data(object $payload);

    public function findAwardeeById(string $id);

    // public function findUserByEmail(string $email);

    // public function createUser(object $payload);

    // public function updateUser(object $payload, string $id);

    // public function deleteUser(string $id);

    public function current_billing(object $payload);
}

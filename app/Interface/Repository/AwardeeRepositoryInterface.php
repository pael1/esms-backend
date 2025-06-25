<?php

namespace App\Interface\Repository;

interface AwardeeRepositoryInterface
{
    public function findMany(object $payload);

    // public function findManyLedger(object $payload);

    public function find_many_childrens(object $payload);

    public function find_many_transactions(object $payload);

    public function find_many_files(object $payload);

    public function find_many_employees_data(object $payload);

    public function findById(string $AwardeeId);

    public function findByOwnerId(string $AwardeeId);

    public function findByUsername(string $email);

    public function create(object $payload);

    public function update(object $payload, string $id);

    public function delete(string $id);

    public function current_billing(object $payload);
}

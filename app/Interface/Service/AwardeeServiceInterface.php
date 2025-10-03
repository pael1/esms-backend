<?php

namespace App\Interface\Service;

interface AwardeeServiceInterface
{
    public function findManyAwardee(object $payload);

    public function awardeeList(object $payload);

    public function find_many_childrens(object $payload);

    public function find_many_transactions(object $payload);

    public function find_many_files(object $payload);

    public function find_many_employees_data(object $payload);

    public function findAwardeeById(string $id);

    public function create(array $payload);

    // public function update(string $id, array $payload);

    public function current_billing(object $payload);
}

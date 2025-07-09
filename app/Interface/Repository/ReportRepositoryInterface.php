<?php

namespace App\Interface\Repository;

interface ReportRepositoryInterface
{
    public function masterlist(object $payload);

    public function masterlist_print(object $payload);

    // public function findById(string $email);

    // public function create(object $payload);

    // public function update(object $payload, string $id);

    // public function delete(string $id);
}

<?php

namespace App\Interface\Service;

interface StallServiceInterface
{
    public function findManyStalls(object $payload);

    public function findStall(object $payload);

    public function findStallNoId(object $payload);

    public function findDescription(string $stallNo, string $renalId);

    public function findStallById(string $stallId);

    public function createStall(object $data);

    public function updateStall(string $stallId, object $data);

    public function deleteStall(string $stallId);
}
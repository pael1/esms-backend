<?php

namespace App\Interface\Service;

interface StallServiceInterface
{
    public function findManyStalls(object $payload);

    public function findDescription(string $stallNo);

    public function findStallById(string $stallId);

    public function createStall(object $data);

    public function updateStall(string $stallId, object $data);

    public function deleteStall(string $stallId);
}
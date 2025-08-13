<?php

namespace App\Service;

use App\Http\Resources\StallListResource;
use App\Interface\Service\StallServiceInterface;
use App\Interface\Repository\StallRepositoryInterface;

class StallService implements StallServiceInterface
{
    private $stallRepository;

    public function __construct(StallRepositoryInterface $stallRepository)
    {
        $this->stallRepository = $stallRepository;
    }
    public function findManyStalls(object $payload)
    {
        $stalls = $this->stallRepository->findManyStalls($payload);

        return StallListResource::collection($stalls);
    }
    public function findStallById(string $stallId)
    {
        return $this->stallRepository->findStallById($stallId);
    }
    public function createStall(object $payload)
    {
        // $payload->building;
        // $payload->extension;
        // $payload->stall_id;
        // $payload->sub_section;
        $payload->merge(['stallNo' => '001110011']);

        return $this->stallRepository->createStall($payload);
    }
    public function updateStall(string $stallId, object $data)
    {
        return $this->stallRepository->updateStall($stallId, $data);
    }
    public function deleteStall(string $stallId)
    {
        return $this->stallRepository->deleteStall($stallId);
    }
}
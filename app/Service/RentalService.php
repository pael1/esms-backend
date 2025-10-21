<?php

namespace App\Service;

use App\Http\Resources\StallListResource;
use App\Http\Resources\StallProfileOnlyResource;
use App\Http\Resources\StallRentalDetResource;
use App\Interface\Repository\RentalRepositoryInterface;
use App\Interface\Service\RentalServiceInterface;

class RentalService implements RentalServiceInterface
{
    private $rentalRepository;

    public function __construct(RentalRepositoryInterface $rentalRepository)
    {
        $this->rentalRepository = $rentalRepository;
    }
    public function findMany(object $payload)
    {
        $rentals = $this->rentalRepository->findMany($payload);

        return StallRentalDetResource::collection($rentals);
    }
    public function findById(string $id)
    {
        $stall = $this->rentalRepository->findById($id);

        return StallRentalDetResource::make($stall);
    }
    public function create(array $payload)
    {
        $rental = $this->rentalRepository->create($payload);

        return StallRentalDetResource::make($rental);
    }
    public function update(string $stallId, array $payload)
    {
        
    }
    public function delete(string $stallId)
    {
        return $this->rentalRepository->delete($stallId);
    }
}
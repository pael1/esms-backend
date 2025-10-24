<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Http\Resources\StallOwnerResource;
use App\Http\Resources\StallOwnerFilesResource;
use App\Interface\Service\StallOwnerServiceInterface;
use App\Interface\Repository\RentalRepositoryInterface;
use App\Interface\Repository\StallOwnerRepositoryInterface;

class StallOwnerService implements StallOwnerServiceInterface
{
    private $StallOwnerRepository;
    private $RentalRepository;

    public function __construct(StallOwnerRepositoryInterface $StallOwnerRepository, RentalRepositoryInterface $rentalRepository)
    {
        $this->StallOwnerRepository = $StallOwnerRepository;
        $this->RentalRepository = $rentalRepository;
    }

    public function findMany(object $payload)
    {
        $stallOwner = $this->StallOwnerRepository->findMany($payload);

        return StallOwnerResource::collection($stallOwner);
    }

    public function findOwner(string $ownerId, string $renalId)
    {
        $stallOwner = $this->StallOwnerRepository->findOwner($ownerId);

        if (!$stallOwner) {
            return response()->json([
                'message' => 'Stall Owner not found',
            ], 400);
        }

        //check if the owner already has rental record
        //view/edit rental record
        if($renalId){
            $rental = $this->RentalRepository->findById($renalId);
            if($rental->ownerId !== $ownerId && $stallOwner->stallRentalDet){
                return response()->json([
                    'message' => 'Stall Owner already has a rental record',
                ], 400);
            }
        } else { //new rental record
            if ($stallOwner->stallRentalDet) {
                return response()->json([
                    'message' => 'Stall Owner already has a rental record',
                ], 400);
            }
        }

        return StallOwnerResource::make($stallOwner);
    }

    public function create(array $payload)
    {
        $stallOwner = $this->StallOwnerRepository->create($payload);
        return StallOwnerResource::make($stallOwner);
    }

    public function show(string $id) {}

    public function update(array $payload, string $id)
    {
        $stallOwner = $this->StallOwnerRepository->update($payload, $id);
        return StallOwnerResource::make($stallOwner);
    }

    public function delete(string $id)
    {
        return $this->StallOwnerRepository->delete($id);
    }
}

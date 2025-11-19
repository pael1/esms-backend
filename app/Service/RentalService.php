<?php

namespace App\Service;

use App\Models\Stallowner;
use App\Http\Resources\StallListResource;
use App\Http\Resources\StallRentalDetResource;
use App\Http\Resources\StallProfileOnlyResource;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Service\RentalServiceInterface;
use App\Interface\Repository\RentalRepositoryInterface;
use App\Interface\Repository\StallOwnerRepositoryInterface;
use App\Repository\StallRepository;

class RentalService implements RentalServiceInterface
{
    private $rentalRepository;
    private $stallOwnerRepository;
    private $stallRepository;
    private $parameterRepository;

    public function __construct(RentalRepositoryInterface $rentalRepository, StallOwnerRepositoryInterface $stallOwnerRepository, StallRepository $stallRepository, ParameterRepositoryInterface $parameterRepository)
    {
        $this->rentalRepository = $rentalRepository;
        $this->stallOwnerRepository = $stallOwnerRepository;
        $this->stallRepository = $stallRepository;
        $this->parameterRepository = $parameterRepository;
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
        //kwaon nako ang details sa stall owner
        $ownerDetails = $this->stallOwnerRepository->findOwner($payload['ownerId']);
        //owner name
        $name = $ownerDetails->firstname . ' ' . $ownerDetails->midinit . ' ' . $ownerDetails->lastname;
        //icheck nako if naay active stall rental ang owner
        $rentalDetails = $this->stallRepository->findStallByOwnerName($name);
        if ($rentalDetails) { 
            $getMarketCode = substr($payload['stallNo'], 0, 2);
            $rentedStallMarketCode = $rentalDetails->stallRentalDet->StallProfile->marketCode;
            //icheck if parehas ang market code
            if($getMarketCode == $rentedStallMarketCode){
                $rentalDetails = $this->parameterRepository->findByFieldIdFieldValue('MARKETCODE', $getMarketCode);
                return response()->json([
                    'message' => 'This owner has an active stall in ' . $rentalDetails->fieldDescription,
                ], 422);
            }
        }

        $rental = $this->rentalRepository->create($payload);

        return StallRentalDetResource::make($rental);
    }
    public function update(string $rentalId, array $payload)
    {
        $rental = $this->rentalRepository->update($rentalId, $payload);
        return StallRentalDetResource::make($rental);
    }
    public function delete(string $stallId)
    {
        return $this->rentalRepository->delete($stallId);
    }

    //cancel rental
    public function cancelRental(string $id)
    {
        $rental = $this->rentalRepository->cancelRental($id);
        return StallRentalDetResource::make($rental);
    }
}
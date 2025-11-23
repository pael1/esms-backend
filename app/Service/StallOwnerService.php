<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Http\Resources\StallOwnerResource;
use App\Http\Resources\StallOwnerFilesResource;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Service\StallOwnerServiceInterface;
use App\Interface\Repository\RentalRepositoryInterface;
use App\Interface\Repository\StallOwnerRepositoryInterface;

class StallOwnerService implements StallOwnerServiceInterface
{
    private $StallOwnerRepository;
    private $RentalRepository;
    private $parameterRepository;

    public function __construct(StallOwnerRepositoryInterface $StallOwnerRepository, RentalRepositoryInterface $rentalRepository, ParameterRepositoryInterface $parameterRepository)
    {
        $this->StallOwnerRepository = $StallOwnerRepository;
        $this->RentalRepository = $rentalRepository;
        $this->parameterRepository = $parameterRepository;
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

    public function findOwnerName(string $query)
    {
        $names = $this->StallOwnerRepository->findOwnerName($query);

        return StallOwnerResource::collection($names);
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

    //icheck kung kani nga owner naa nay stall nga active
    public function checkDetails(object $payload)
    {
        $stallOwners = $this->StallOwnerRepository->checkDetails($payload);
        $firstMarketCode = null;
        $firstMarketName = null;
        
        foreach ($stallOwners as $stallOwner) {
            $code = substr($stallOwner->stallRentalDet->stallNo, 0, 2);
            $market = $this->parameterRepository->findByFieldIdFieldValue('MARKETCODE', $code);
            $stallOwner->market_name = $market->fieldDescription ?? null;
            if ($firstMarketCode === null) {
                $firstMarketCode = $code; // set the first stall's market code
                $firstMarketName = $market->fieldDescription;
            } elseif ($firstMarketCode !== $code) {
                $secondMarketName = $market->fieldDescription;
                return response()->json([
                    'message' => "This owner has two active stalls in different markets [{$firstMarketName}, {$secondMarketName}].",
                ], 422);
            }
        }

        //check if more than 2 stalls auto reject
        if (count($stallOwners) > 2) {
            $marketsCount = count($stallOwners);
            return response()->json([
                'message' => "This owner has {$marketsCount} active stalls in different markets.",
            ], 422);
        }

        return StallOwnerResource::collection($stallOwners);
    }

}

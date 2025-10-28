<?php

namespace App\Service;

use App\Http\Resources\StallListResource;
use App\Http\Resources\StallProfileOnlyResource;
use App\Http\Resources\StallProfileResource;
use App\Interface\Service\StallServiceInterface;
use App\Interface\Repository\StallRepositoryInterface;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Service\RentalServiceInterface;

class StallService implements StallServiceInterface
{
    private $stallRepository;
    private $parameterRepository;
    private $rentalRepository;

    public function __construct(StallRepositoryInterface $stallRepository, ParameterRepositoryInterface $parameterRepository, RentalServiceInterface $rentalRepository)
    {
        $this->stallRepository = $stallRepository;
        $this->parameterRepository = $parameterRepository;
        $this->rentalRepository = $rentalRepository;
    }
    public function findManyStalls(object $payload)
    {
        $stalls = $this->stallRepository->findManyStalls($payload);

        return StallListResource::collection($stalls);
    }

    public function findStall(object $payload)
    {
        $stall = $this->stallRepository->findStall($payload);
        logger($stall);
        return StallProfileOnlyResource::collection($stall);
    }

    public function findStallNoId(object $payload)
    {
        $stall = $this->stallRepository->findStallNoId($payload);

        return StallProfileOnlyResource::collection($stall);
    }

    public function findDescription(string $stallNo, string $renalId)
    {
        $stall = $this->stallRepository->findDescription($stallNo);

        if (!$stall) {
            return response()->json([
                'message' => 'Stall not found',
            ], 400);
        }

        //check if the stall is already rented by other owner
        //view/edit rental record
        if($renalId){
            $rental = $this->rentalRepository->findById($renalId);
            if($rental->stallNo !== $stallNo && $stall->stallStatus === 'REN'){
                return response()->json([
                    'message' => 'Stall has already been rented',
                ], 400);
            }
        } else { //new rental record
            if ($stall->stallStatus === 'REN') {
                return response()->json([
                    'message' => 'Stall has already been rented',
                ], 400);
            }
        }

        return StallListResource::make($stall);
    }
    public function findStallById(string $stallId)
    {
        $stall = $this->stallRepository->findStallById($stallId);

        return StallProfileOnlyResource::make($stall);
    }
    public function createStall(object $payload)
    {
        // get descriptions
        $building = $this->parameterRepository->findByFieldIdFieldValue('STRUCTCODE', $payload->building);
        $section = $this->parameterRepository->findByFieldIdFieldValue('SECTIONCODE', $payload->section);
        $sectionSub = ($payload->sub_section) ? $this->parameterRepository->findByFieldIdFieldValue('SERIESCODE', $payload->sub_section) : null;
        $market = $this->parameterRepository->findByFieldIdFieldValue('MARKETCODE', $payload->market);
        // if (!empty($payload->cfsi)) {
        //     $cfsi = $this->parameterRepository->findByFieldIdFieldValue('CFSI', $payload->cfsi);
        //     $payload->cfsi = $cfsi?->fieldDescription ?? '';
        // } else {
        //     $payload->cfsi = '';
        // }

        $hasSubSection = ($payload->sub_section) ? ' ('.$sectionSub.') ' : ' ';
        $marketDescription = $building->fieldDescription.', '.$section->fieldDescription.' '.$payload->type.''.$hasSubSection.''.$market->fieldDescription;
        
        $sectionFormat = ($payload->sub_section) ? $payload->sub_section : $payload->section . '00';
        $sectionCode = $payload->building.''.$sectionFormat;
        $stallNoFormat = $payload->market.''.$sectionCode.''.$payload->stall_id . '' . $payload->extension;
        
        $payload->stallNo = $stallNoFormat;
        $payload->stallDescription = $marketDescription;
        $payload->sectionCode = $sectionCode;
        // $payload->stallStatus = 'available';
        $payload->stallNoId = $payload->stall_id . '' . $payload->extension;

        return $this->stallRepository->createStall($payload);
    }
    public function updateStall(string $stallId, object $payload)
    {
        try {
            // get descriptions
            $building = $this->parameterRepository->findByFieldIdFieldValue('STRUCTCODE', $payload->building);
            $section = $this->parameterRepository->findByFieldIdFieldValue('SECTIONCODE', $payload->section);
            $sectionSub = ($payload->sub_section) ? $this->parameterRepository->findByFieldIdFieldValue('SERIESCODE', $payload->sub_section) : null;
            $market = $this->parameterRepository->findByFieldIdFieldValue('MARKETCODE', $payload->market);
            // if (!empty($payload->cfsi)) {
            //     $cfsi = $this->parameterRepository->findByFieldIdFieldValue('CFSI', $payload->cfsi);
            //     $payload->cfsi = $cfsi?->fieldDescription ?? '';
            // } else {
            //     $payload->cfsi = '';
            // }

            $hasSubSection = ($payload->sub_section) ? ' ('.$sectionSub.') ' : ' ';
            $marketDescription = $building->fieldDescription.', '.$section->fieldDescription.' '.$payload->type.''.$hasSubSection.''.$market->fieldDescription;
            
            $sectionFormat = ($payload->sub_section) ? $payload->sub_section : $payload->section . '00';
            $stallNoFormat = $payload->market.''.$sectionFormat.''.$payload->stall_id . '' . $payload->extension;
            
            $payload->stallNo = $stallNoFormat;
            $payload->stallDescription = $marketDescription;
            $payload->sectionCode = $payload->building.''.$sectionFormat;
            $payload->stallNoId = $payload->stall_id . '' . $payload->extension;

            return $this->stallRepository->updateStall($stallId, $payload);
        } catch (\Throwable $th) {
            //throw $th;
            logger($th);
        }
    }
    public function deleteStall(string $stallId)
    {
        return $this->stallRepository->deleteStall($stallId);
    }
}
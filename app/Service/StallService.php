<?php

namespace App\Service;

use App\Http\Resources\StallListResource;
use App\Http\Resources\StallProfileOnlyResource;
use App\Http\Resources\StallProfileResource;
use App\Interface\Service\StallServiceInterface;
use App\Interface\Repository\StallRepositoryInterface;
use App\Interface\Repository\ParameterRepositoryInterface;

class StallService implements StallServiceInterface
{
    private $stallRepository;
    private $parameterRepository;

    public function __construct(StallRepositoryInterface $stallRepository, ParameterRepositoryInterface $parameterRepository)
    {
        $this->stallRepository = $stallRepository;
        $this->parameterRepository = $parameterRepository;
    }
    public function findManyStalls(object $payload)
    {
        $stalls = $this->stallRepository->findManyStalls($payload);

        return StallListResource::collection($stalls);
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
        if (!empty($payload->cfsi)) {
            $cfsi = $this->parameterRepository->findByFieldIdFieldValue('CFSI', $payload->cfsi);
            $payload->cfsi = $cfsi?->fieldDescription ?? '';
        } else {
            $payload->cfsi = '';
        }

        $hasSubSection = ($payload->sub_section) ? ' ('.$sectionSub.') ' : ' ';
        $marketDescription = $building->fieldDescription.', '.$section->fieldDescription.' '.$payload->type.''.$hasSubSection.''.$market->fieldDescription;
        
        $sectionFormat = ($payload->sub_section) ? $payload->sub_section : $payload->section . '00';
        $stallNoFormat = $payload->market.''.$sectionFormat.''.$payload->stall_id . '' . $payload->extension;
        
        $payload->stallNo = $stallNoFormat;
        $payload->stallDescription = $marketDescription;
        $payload->sectionCode = $payload->building.''.$sectionFormat;
        $payload->stallNoId = $payload->stall_id . '' . $payload->extension;

        return $this->stallRepository->createStall($payload);
    }
    public function updateStall(string $stallId, object $payload)
    {
        // get descriptions
        $building = $this->parameterRepository->findByFieldIdFieldValue('STRUCTCODE', $payload->building);
        $section = $this->parameterRepository->findByFieldIdFieldValue('SECTIONCODE', $payload->section);
        $sectionSub = ($payload->sub_section) ? $this->parameterRepository->findByFieldIdFieldValue('SERIESCODE', $payload->sub_section) : null;
        $market = $this->parameterRepository->findByFieldIdFieldValue('MARKETCODE', $payload->market);
        if (!empty($payload->cfsi)) {
            $cfsi = $this->parameterRepository->findByFieldIdFieldValue('CFSI', $payload->cfsi);
            $payload->cfsi = $cfsi?->fieldDescription ?? '';
        } else {
            $payload->cfsi = '';
        }

        $hasSubSection = ($payload->sub_section) ? ' ('.$sectionSub.') ' : ' ';
        $marketDescription = $building->fieldDescription.', '.$section->fieldDescription.' '.$payload->type.''.$hasSubSection.''.$market->fieldDescription;
        
        $sectionFormat = ($payload->sub_section) ? $payload->sub_section : $payload->section . '00';
        $stallNoFormat = $payload->market.''.$sectionFormat.''.$payload->stall_id . '' . $payload->extension;
        
        $payload->stallNo = $stallNoFormat;
        $payload->stallDescription = $marketDescription;
        $payload->sectionCode = $payload->building.''.$sectionFormat;
        $payload->stallNoId = $payload->stall_id . '' . $payload->extension;

        return $this->stallRepository->updateStall($stallId, $payload);
    }
    public function deleteStall(string $stallId)
    {
        return $this->stallRepository->deleteStall($stallId);
    }
}
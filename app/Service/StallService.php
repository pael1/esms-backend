<?php

namespace App\Service;

use App\Http\Resources\StallListResource;
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
        return $this->stallRepository->findStallById($stallId);
    }
    public function createStall(object $payload)
    {

        // get descriptions
        $building = $this->parameterRepository->findByFieldIdFieldValue('STRUCTCODE', $payload->building);
        $section = $this->parameterRepository->findByFieldIdFieldValue('SECTIONCODE', $payload->section);
        $sectionSub = ($payload->sub_section) ? $this->parameterRepository->findByFieldIdFieldValue('SERIESCODE', $payload->sub_section) : null;
        $type = $this->parameterRepository->findByFieldIdFieldValue('STALLTYPE', $payload->type);
        $market = $this->parameterRepository->findByFieldIdFieldValue('MARKETCODE', $payload->market);
        $cfsi = $this->parameterRepository->findByFieldIdFieldValue('CFSI', $payload->cfsi);

        $hasSubSection = ($payload->sub_section) ? ' ('.$sectionSub.') ' : ' ';
        $marketDescription = $building->fieldDescription.', '.$section->fieldDescription.' '.$type->fieldDescription.''.$hasSubSection.''.$market->fieldDescription;
        
        $sectionFormat = ($payload->sub_section) ? $payload->sub_section : $payload->section . '00';
        $stallNoFormat = $payload->market.''.$sectionFormat.''.$payload->stall_id . '' . $payload->extension;
        
        $payload->stallNo = $stallNoFormat;
        $payload->stallDescription = $marketDescription;
        $payload->sectionCode = $payload->building.''.$sectionFormat;
        // $payload->stallStatus = 'available';
        $payload->stallNoId = $payload->stall_id . '' . $payload->extension;
        $payload->cfsi = $cfsi->fieldDescription;
        $payload->type = $type->fieldDescription;

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
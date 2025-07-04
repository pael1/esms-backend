<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AwardeeListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stallOwnerId' => $this->stallOwnerId,
            'ownerId' => $this->ownerId,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'midinit' => $this->midinit,
            'full_name' => $this->firstname.' '.$this->midinit.'. '.$this->lastname,
            'civilStatus' => $this->civilStatus,
            'address' => $this->address,
            'spouseLastname' => $this->spouseLastname,
            'spouseFirstname' => $this->spouseFirstname,
            'spouseMidint' => $this->spouseMidint,
            'spouse_full_name' => $this->spouseFirstname.' '.$this->spouseMidint.'. '.$this->spouseLastname,
            'ownerStatus' => $this->ownerStatus,
            'attachIdPhoto' => $this->attachIdPhoto,
            'dateRegister' => $this->dateRegister,
            'contactnumber' => $this->contactnumber,
            'leaseContract' => $this->leaseContract,
            // 'stallno' => $this->stallno,
            // 'ownername' => $this->ownername,
            // 'acctname' => $this->acctname,
            // 'acctid' => $this->acctid,
            // 'isStallOwner' => $this->isStallOwner,
            // 'stallNoDash' => $this->stallNoDash,
            'stallDescription' => $this->stallDescription,
            'stallNoId' => $this->stallNoId,
            // 'StallIDZeros' => $this->StallIDZeros,
            // 'market' => $this->market,
            'stallDetailId' => $this->stallDetailId,
            // 'numberOfLeaseContract' => $this->numberOfLeaseContract,
            // 'numberOfAccounts' => $this->numberOfAccounts,
            'rentalStatus' => $this->rentalStatus,
            'contractStartDate' => $this->contractStartDate,
            'contractYear' => $this->contractYear,
            'contractEndDate' => $this->contractEndDate,
            'busID' => $this->busID,
            'busPlateNo' => $this->busPlateNo,
            'busDateStart' => $this->busDateStart,
            'capital' => $this->capital,
            'lineOfBusiness' => $this->lineOfBusiness,
            'STALLOWNER_stallOwnerId' => $this->STALLOWNER_stallOwnerId,
            'documentFiles' => $this->documentFiles,
            'busIDStatus' => $this->busIDStatus,
            'stallProfileId' => $this->stallProfileId,
            'stallType' => $this->stallType,
            'sectionCode' => $this->sectionCode,
            'marketCode' => $this->marketCode,
            'picPath' => $this->picPath,
            'stallArea' => $this->stallArea,
            'stallClass' => $this->stallClass,
            'CFSI' => $this->CFSI,
            'stallStatus' => $this->stallStatus,
            'fixRatePerSqm' => $this->fixRatePerSqm,
            'ratePerDay' => $this->ratePerDay,
            'ratePerMonth' => $this->ratePerMonth,
            'StallAreaExt' => $this->StallAreaExt,
        ];
    }
}

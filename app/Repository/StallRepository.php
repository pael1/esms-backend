<?php

namespace App\Repository;

use App\Models\Stallowner;
use App\Models\Stallprofile;
use App\Interface\Repository\StallRepositoryInterface;

class StallRepository implements StallRepositoryInterface
{
    public function findManyStalls(object $payload)
    {
        $query = Stallprofile::with(['stallRental', 'stallRental.stallOwner'])
                ->filter($payload->all())
                ->orderBy('stallProfileId', 'desc');

        return $query->paginate(10);
    }

    public function findStall(object $payload)
    {
        $query = Stallprofile::whereNull('stallStatus');

        if (!empty($payload->type)) {
            $query->where('stallType', $payload->type);
        }

        if (!empty($payload->marketcode)) {
            $query->where('marketCode', $payload->marketcode);
        }

        if (!empty($payload->sectioncode)) {
            $query->where('sectionCode', $payload->sectioncode);
        }

        if (!empty($payload->stallNoId)) {
            $query->where('stallNoId', $payload->stallNoId);
        }

        if (!empty($payload->marketcode) && empty($payload->sectioncode)) {
            $query->groupBy('sectionCode');
        }
        
        $query->orderBy('stallProfileId', 'desc');

        return $query->get();
    }

    public function findStallNoId(object $payload)
    {
        $query = Stallprofile::whereNull('stallStatus')
        ->filter($payload->all())
        ->orderBy('stallProfileId', 'desc');

        return $query->get();
    }

    public function findDescription(string $stallNo)
    {
        return Stallprofile::where('stallNo', $stallNo)
        ->select(['stallDescription', 'stallStatus', 'stallNo'])
        ->first();
    }
    public function findStallById(string $stallId)
    {
        $stall = Stallprofile::findOrFail($stallId);
        return $stall;
    }
    public function createStall(object $payload)
    {
        $sp = new Stallprofile();
        $sp->stallArea = $payload->area;
        $sp->StallAreaExt = $payload->area_extension;
        $sp->stallNo = $payload->stallNo;
        $sp->CFSI = $payload->cfsi;
        $sp->stallClass = $payload->class;
        $sp->stallDescription = $payload->stallDescription;
        $sp->marketCode = $payload->market;
        $sp->sectionCode = $payload->sectionCode;
        $sp->stallNoId = $payload->stallNoId;
        $sp->stallType = $payload->type;
        $sp->section_id = $payload->section;
        $sp->sub_section_id = $payload->sub_section;
        $sp->building_id = $payload->building;
        $sp->stall_id_ext = $payload->extension;
        $sp->stall_no_id = $payload->stall_id;
        $sp->save();

        return $sp->fresh();
    }
    public function updateStall(string $stallId, object $payload)
    {
        $sp = Stallprofile::where('stallProfileId', $stallId)->firstOrFail();
        $sp->stallArea = $payload->area;
        $sp->StallAreaExt = $payload->area_extension;
        // $sp->stallNo = $payload->stallNo;
        $sp->CFSI = $payload->cfsi;
        $sp->stallClass = $payload->class;
        // $sp->stallDescription = $payload->stallDescription;
        $sp->marketCode = $payload->market;
        // $sp->sectionCode = $payload->sectionCode;
        // $sp->stallNoId = $payload->stallNoId;
        $sp->stallType = $payload->type;
        $sp->section_id = $payload->section;
        $sp->sub_section_id = $payload->sub_section;
        $sp->building_id = $payload->building;
        $sp->stall_id_ext = $payload->extension;
        $sp->stall_no_id = $payload->stall_id;
        $sp->save();

        return $sp->fresh();
    }
    public function deleteStall(string $stallId)
    {
        // Implementation for deleting a stall
    }

    public function findStallByOwnerName(string $name)
    {
        return Stallowner::with('stallRentalDet','stallRentalDet.StallProfile')
            ->where('rental_status', 'rented')
            ->whereRaw("
                CONCAT(
                    firstname, ' ',
                    TRIM(CONCAT(COALESCE(CONCAT(midinit, ' '), ''), lastname))
                ) LIKE ?
            ", ["%{$name}%"])
            ->first();
    }
}
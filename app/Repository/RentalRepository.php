<?php

namespace App\Repository;

use App\Interface\Repository\RentalRepositoryInterface;
use App\Models\Stallprofile;
use App\Models\Stallrentaldet;

class RentalRepository implements RentalRepositoryInterface
{
    public function findMany(object $payload)
    {
        $query = Stallrentaldet::with(['stallProfile', 'stallOwner'])
                ->filter($payload->all())
                ->orderBy('stallDetailId', 'desc');

        return $query->paginate(10);
    }
    public function findById(string $id)
    {
        $rental = Stallrentaldet::findOrFail($id);
        return $rental;
    }
    public function create(object $payload)
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
    public function update(string $stallId, object $payload)
    {
        $sp = Stallprofile::where('stallProfileId', $stallId)->firstOrFail();
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
    public function delete(string $stallId)
    {
        // Implementation for deleting a stall
    }
}
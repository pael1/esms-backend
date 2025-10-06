<?php

namespace App\Repository;

use App\Interface\Repository\StallRepositoryInterface;
use App\Models\Stallprofile;

class StallRepository implements StallRepositoryInterface
{
    public function findManyStalls(object $payload)
    {
        // $query = Stallprofile::query()
        //     ->leftJoin('stallrentaldet as b', 'stallprofile.stallno', '=', 'b.stallno')
        //     ->leftJoin('stallowner as a', 'b.STALLOWNER_stallOwnerId', '=', 'a.stallownerid')
        //     ->where('stallprofile.marketcode', $payload->marketcode)
        //     ->whereRaw('SUBSTRING(stallprofile.sectionCode, 3, 2) = ?', [$payload->section])
        //     ->when($payload->name, function ($q) use ($payload) {
        //         $fullname = trim(preg_replace('/\s+/', ' ', $payload->name));
        //         $q->whereRaw("CONCAT(a.firstname, ' ', a.lastname) LIKE ?", ["%{$fullname}%"]);
        //     })
        //     ->select([
        //         'stallprofile.*',
        //         'a.firstname',
        //         'a.midinit',
        //         'a.lastname',
        //         'b.rentalStatus',
        //     ])
        //     ->orderBy('stallprofile.stallNoId', 'asc');

        // return $query->paginate(10);
        $query = Stallprofile::with(['stallRental', 'stallRental.stallOwner'])
                ->filter($payload->all())
                ->orderBy('stallNoId', 'desc');

        return $query->paginate(10);
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

        //dli mo gana kay dli same ug name ang payload ug specific fields name sa db
        // $stall = Stallprofile::findOrFail($stallId);
        // $stall->update((array) $payload);
        // return $stall->fresh();
    }
    public function deleteStall(string $stallId)
    {
        // Implementation for deleting a stall
    }
}
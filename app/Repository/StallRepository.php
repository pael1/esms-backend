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
        // Implementation for finding a stall by ID
    }
    public function createStall(object $payload)
    {
        // logger($payload);
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
        // $sp->months = $payload->sub_section;
        $sp->stallType = $payload->type;
        $sp->save();

        return $sp->fresh();
    }
    public function updateStall(string $stallId, object $data)
    {
        // Implementation for updating a stall
    }
    public function deleteStall(string $stallId)
    {
        // Implementation for deleting a stall
    }
}
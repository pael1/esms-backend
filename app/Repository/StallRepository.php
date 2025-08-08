<?php

namespace App\Repository;

use App\Interface\Repository\StallRepositoryInterface;
use App\Models\Stallprofile;

class StallRepository implements StallRepositoryInterface
{
    public function findManyStalls(object $payload)
    {
        $query = Stallprofile::query()
            ->leftJoin('stallrentaldet as b', 'stallprofile.stallno', '=', 'b.stallno')
            ->leftJoin('stallowner as a', 'b.STALLOWNER_stallOwnerId', '=', 'a.stallownerid')
            ->where('stallprofile.marketcode', $payload->marketcode)
            ->whereRaw('SUBSTRING(stallprofile.sectionCode, 3, 2) = ?', [$payload->section])
            ->when($payload->name, function ($q) use ($payload) {
                $fullname = trim(preg_replace('/\s+/', ' ', $payload->name));
                $q->whereRaw("CONCAT(a.firstname, ' ', a.lastname) LIKE ?", ["%{$fullname}%"]);
            })
            ->select([
                'stallprofile.*',
                'a.firstname',
                'a.midinit',
                'a.lastname',
                'b.rentalStatus',
            ])
            ->orderBy('stallprofile.stallNoId', 'asc');

        return $query->paginate(10);
    }
    public function findStallById(string $stallId)
    {
        // Implementation for finding a stall by ID
    }
    public function createStall(object $data)
    {
        // Implementation for creating a stall
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
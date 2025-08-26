<?php

namespace App\Repository;

use App\Interface\Repository\AwardeeRepositoryInterface;
use App\Models\ExpirationDate;
use App\Models\Parameter;
use App\Models\StallOP;
use App\Models\Stallowner;
use App\Models\StallOwnerAccount;
use App\Models\StallOwnerChild;
use App\Models\StallOwnerEmp;
use App\Models\StallOwnerFiles;
use App\Models\StallProfileViews;
use App\Models\Stallrentaldet;
use App\Models\User;
use App\Models\UserAccount;
use App\Pops\Api;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AwardeeRepository implements AwardeeRepositoryInterface
{

    public function create(array $payload)
    {
        // $stallOwner = new Stallowner();
        //  // from payload
        // $stallOwner->lastname        = $payload->lastname ?? null;
        // $stallOwner->firstname       = $payload->firstname ?? null;
        // $stallOwner->midinit         = $payload->midinit ?? null;
        // $stallOwner->civilStatus     = $payload->civilStatus ?? null;
        // $stallOwner->address         = $payload->address ?? null;
        // $stallOwner->spouseLastname  = $payload->spouseLastname ?? null;
        // $stallOwner->spouseFirstname = $payload->spouseFirstname ?? null;
        // $stallOwner->spouseMidint    = $payload->spouseMidint ?? null;
        // $stallOwner->attachIdPhoto   = $payload->attachIdPhoto ?? null;
        // $stallOwner->contactnumber   = $payload->contactnumber ?? null;

        // // fixed / generated values
        // $stallOwner->ownerStatus   = "ACTIVE";
        // $stallOwner->ownerId       = 25000057; // you might want to auto-generate this instead
        // $stallOwner->dateRegister  = now();
        // $stallOwner->save();

        // return $stallOwner->fresh();

        $payload['ownerStatus']  = "ACTIVE";
        $payload['ownerId']      = "25000057";
        $payload['dateRegister'] = now();
        $stallOwner = Stallowner::create($payload);
        foreach ($payload['children'] as $child) {
            $stallOwner->children()->create([
                'ownerId'      => $stallOwner->ownerId,
                'childName'      => $child['childName'],
                'childBDate' => $child['childBDate'],
            ]);
        }

        return $stallOwner->fresh();
    }

    public function findMany(object $payload)
    {
        $query = DB::table('stallowner as a')
            ->leftJoin('stallrentaldet as b', 'a.stallownerid', '=', 'b.STALLOWNER_stallOwnerId')
            ->leftJoin('stallprofile as c', 'b.stallno', '=', 'c.stallno')
            ->where('a.ownerStatus', 'ACTIVE')
            ->where('c.marketcode', $payload->marketcode)
            ->where('c.stallType', $payload->type)
            ->whereRaw('SUBSTRING(c.sectionCode, 3, 2) = ?', [$payload->section])
            ->where('c.stallNoId', '!=', '')
            ->whereNotNull('c.stallNoId')
            ->when($payload->name, function ($q) use ($payload) {
                $fullname = trim(preg_replace('/\s+/', ' ', $payload->name));
                $q->whereRaw("CONCAT(a.firstname, ' ', a.lastname) LIKE ?", ["%{$fullname}%"]);
            })
            ->orderBy('c.stallNoId', 'asc');

        return $query->paginate(10);
    }

    public function find_many_childrens(object $payload)
    {
        return StallOwnerChild::where('ownerId', $payload->ownerId)->paginate(10);
    }

    public function find_many_transactions(object $payload)
    {
        return StallOP::where('ownerId', $payload->ownerId)
            ->orderBy('postDateTime', 'desc')
            ->groupBy('OPRefId')
            ->paginate(10);
    }

    public function find_many_files(object $payload)
    {
        return StallOwnerFiles::where('ownerId', $payload->ownerId)->paginate(10);
    }

    public function find_many_employees_data(object $payload)
    {
        return StallOwnerEmp::where('ownerId', $payload->ownerId)->paginate(10);
    }

    public function findById(string $ownerID)
    {
        return Stallowner::with([
            'stallRentalDet',
            'stallRentalDet.stallProfile',
            // 'stallRentalDet.stallProfileViews',
            'stallRentalDet.stallProfile.signatory',
            'stallRentalDet.stallProfile.officecode',

        ])
            ->where('ownerId', $ownerID)
            ->first();
    }

    public function findByOwnerId(string $ownerID)
    {
        return Stallowner::with([
            'childrens',
            'ledger',
        ])->where('ownerId', $ownerID)->first();
    }

    // //CONVERSION
    // public function manyStalls(object $payload)
    // {
    //     $query = StallProfile::query()
    //         ->leftJoin('stallrentaldet as b', 'stallprofile.stallno', '=', 'b.stallno')
    //         ->leftJoin('stallowner as a', 'b.STALLOWNER_stallOwnerId', '=', 'a.stallownerid')
    //         ->where('stallprofile.marketcode', $payload->marketcode)
    //         ->whereRaw('SUBSTRING(stallprofile.sectionCode, 3, 2) = ?', [$payload->section])
    //         ->when($payload->name, function ($q) use ($payload) {
    //             $fullname = trim(preg_replace('/\s+/', ' ', $payload->name));
    //             $q->whereRaw("CONCAT(a.firstname, ' ', a.lastname) LIKE ?", ["%{$fullname}%"]);
    //         })
    //         ->select('stallprofile.*', 'b.*', 'a.*')
    //         ->orderBy('stallprofile.stallNoId', 'asc');

    //     return $query->paginate(10);
    // }
}

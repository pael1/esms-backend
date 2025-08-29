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
        //generate ownerId
        $nextOwnerId = Stallowner::max('ownerId') + 1;

        $payload['ownerId'] = str_pad($nextOwnerId, 8, '0', STR_PAD_LEFT);
        $payload['ownerStatus']  = "ACTIVE";
        $payload['dateRegister'] = now();
        
        // logger($payload);
        if (!empty($payload['attachIdPhoto'])) {

            $uploadedFile = $payload['attachIdPhoto'];
            $filename = time() . '_' . $uploadedFile->getClientOriginalName();

            $payload['attachIdPhoto'] = $uploadedFile->storeAs("profile_pic/{$payload['ownerId']}", $filename, 'public');
        }

        $stallOwner = Stallowner::create($payload);

        if (!empty($payload['children'])) {
            foreach ($payload['children'] as $child) {
                $stallOwner->children()->create([
                    'ownerId'     => $stallOwner->ownerId,
                    'childName'   => $child['childName'] ?? null,
                    'childBDate'  => $child['childBDate'] ?? null,
                ]);
            }
        }

        if (!empty($payload['employees'])) {
            foreach ($payload['employees'] as $employee) {
                $stallOwner->employees()->create([
                    'ownerId'       => $stallOwner->ownerId,
                    'employeeName'  => $employee['employeeName'] ?? null,
                    'dateOfBirth'   => $employee['dateOfBirth'] ?? null,
                    'age'           => $employee['age'] ?? null,
                    'address'       => $employee['address'] ?? null,
                ]);
            }
        }

        if (!empty($payload['files'])) {
            foreach ($payload['files'] as $file) {
                $uploadedFile = $file['filePath']; // real UploadedFile object
                $filename = time() . '_' . $uploadedFile->getClientOriginalName();

                $path = $uploadedFile->storeAs("files/{$stallOwner->ownerId}", $filename, 'public');

                $stallOwner->files()->create([
                    'attachFileType' => $file['attachFileType'] ?? null,
                    'filePath'       => $path,
                ]);
            }
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

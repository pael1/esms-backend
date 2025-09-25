<?php

namespace App\Repository;

use Exception;
use App\Pops\Api;
use Carbon\Carbon;
use App\Models\User;
use App\Models\StallOP;
use App\Models\Parameter;
use App\Models\Stallowner;
use App\Models\UserAccount;
use Illuminate\Support\Str;
use App\Models\StallOwnerEmp;
use Illuminate\Http\Response;
use App\Models\ExpirationDate;
use App\Models\Stallrentaldet;
use App\Models\StallOwnerChild;
use App\Models\StallOwnerFiles;
use App\Models\StallOwnerAccount;
use App\Models\StallProfileViews;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\AwardeeRepositoryInterface;

class AwardeeRepository implements AwardeeRepositoryInterface
{

    public function create(array $payload)
    {
        try {
            return DB::transaction(function () use ($payload) {
                // generate ownerId
                $nextOwnerId = Stallowner::max('ownerId') + 1;

                $payload['ownerId']      = str_pad($nextOwnerId, 8, '0', STR_PAD_LEFT);
                $payload['ownerStatus']  = "ACTIVE";
                $payload['dateRegister'] = now();
                
                // Profile Photo Upload
                if (!empty($payload['attachIdPhoto'])) {
                    $uploadedFile = $payload['attachIdPhoto'];
                    $filename = time() . '_' . $uploadedFile->getClientOriginalName();

                    $payload['attachIdPhoto'] = $uploadedFile->storeAs(
                        "profile_pic/{$payload['ownerId']}", 
                        $filename, 
                        'public'
                    );
                }

                // Create Stall Owner
                $stallOwner = Stallowner::create($payload);

                // Save Children
                if (!empty($payload['children'])) {
                    foreach ($payload['children'] as $child) {
                        $stallOwner->children()->create([
                            'ownerId'    => $stallOwner->ownerId,
                            'childName'  => $child['childName'] ?? null,
                            'childBDate' => $child['childBDate'] ?? null,
                        ]);
                    }
                }

                // Save Employees
                if (!empty($payload['employees'])) {
                    foreach ($payload['employees'] as $employee) {
                        $stallOwner->employees()->create([
                            'ownerId'      => $stallOwner->ownerId,
                            'employeeName' => $employee['employeeName'] ?? null,
                            'dateOfBirth'  => $employee['dateOfBirth'] ?? null,
                            'age'          => $employee['age'] ?? null,
                            'address'      => $employee['address'] ?? null,
                        ]);
                    }
                }

                // Save Files
                if (!empty($payload['files'])) {
                    foreach ($payload['files'] as $file) {
                        $uploadedFile = $file['filePath']; // real UploadedFile object
                        $filename = time() . '_' . $uploadedFile->getClientOriginalName();

                        $path = $uploadedFile->storeAs(
                            "files/{$stallOwner->ownerId}", 
                            $filename, 
                            'public'
                        );

                        $stallOwner->files()->create([
                            'attachFileType' => $file['attachFileType'] ?? null,
                            'filePath'       => $path,
                        ]);
                    }
                }

                return $stallOwner->fresh();
            });
        } catch (Exception $e) {
            // Rollback is automatic if an exception occurs
            Log::error('Failed to create Stall Owner', [
                'error'   => $e->getMessage(),
                'payload' => $payload
            ]);

            // You can throw a custom exception if you want
            throw new Exception("Something went wrong while saving Stall Owner. Please try again.");
        }
    }

    public function update(string $id, array $payload)
    {
        try {
            return DB::transaction(function () use ($id, $payload) {
                // Find existing Stall Owner
                // $stallOwner = Stallowner::findOrFail($id);
                $stallOwner = Stallowner::where('ownerId', $id)->firstOrFail();
               
                // Profile Photo Upload
                if (!empty($payload['attachIdPhoto'])) {
                    $uploadedFile = $payload['attachIdPhoto'];
                    $filename = time() . '_' . $uploadedFile->getClientOriginalName();
                    $path = $uploadedFile->storeAs(
                        "profile_pic/{$stallOwner->ownerId}", 
                        $filename, 
                        'public'
                    );
                    $payload['attachIdPhoto'] = $path;
                }

                // Update Stall Owner (ignore related fields)
                $stallOwner->update(
                    collect($payload)->except(['children','employees','files'])->toArray()
                );

                // Update Children
                if (isset($payload['children'])) {
                    // $stallOwner->children()->delete();
                    foreach ($payload['children'] as $child) {
                        $stallOwner->children()->create([
                            'ownerId'    => $stallOwner->ownerId,
                            'childName'  => $child['childName'] ?? null,
                            'childBDate' => $child['childBDate'] ?? null,
                        ]);
                    }
                }

                // Update Employees
                if (isset($payload['employees'])) {
                    // $stallOwner->employees()->delete();
                    foreach ($payload['employees'] as $employee) {
                        $stallOwner->employees()->create([
                            'ownerId'      => $stallOwner->ownerId,
                            'employeeName' => $employee['employeeName'] ?? null,
                            'dateOfBirth'  => $employee['dateOfBirth'] ?? null,
                            'age'          => $employee['age'] ?? null,
                            'address'      => $employee['address'] ?? null,
                        ]);
                    }
                }

                // Update Files
                if (isset($payload['files'])) {
                    // $stallOwner->files()->delete();
                    foreach ($payload['files'] as $file) {
                        $uploadedFile = $file['filePath']; // real UploadedFile object
                        $filename = time() . '_' . $uploadedFile->getClientOriginalName();

                        $path = $uploadedFile->storeAs(
                            "files/{$stallOwner->ownerId}", 
                            $filename, 
                            'public'
                        );

                        $stallOwner->files()->create([
                            'attachFileType' => $file['attachFileType'] ?? null,
                            'filePath'       => $path,
                        ]);
                    }
                }

                return $stallOwner->fresh();
            });
        } catch (Exception $e) {
            Log::error('Failed to update Stall Owner', [
                'error'   => $e->getMessage(),
                'payload' => $payload
            ]);

            throw new Exception("Something went wrong while updating Stall Owner. Please try again.");
        }
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
        // logger($payload);
        // $query = DB::table('stallowner as a')
        //     ->where('a.ownerStatus', 'ACTIVE')
        //     ->when($payload->name, function ($q) use ($payload) {
        //         $fullname = trim(preg_replace('/\s+/', ' ', $payload->name));
        //         $q->whereRaw("CONCAT(a.firstname, ' ', a.lastname) LIKE ?", ["%{$fullname}%"]);
        //     });

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

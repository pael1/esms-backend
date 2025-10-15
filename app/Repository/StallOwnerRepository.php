<?php

namespace App\Repository;

use Exception;
use App\Models\User;
use App\Models\Stallowner;
use App\Models\StallOwnerEmp;
use Illuminate\Http\Response;
use App\Models\StallOwnerChild;
use App\Models\StallOwnerFiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\FileRepositoryInterface;
use App\Interface\Repository\StallOwnerRepositoryInterface;

class StallOwnerRepository implements StallOwnerRepositoryInterface
{
    public function findMany(object $payload)
    {
        return Stallowner::with(['children', 'employees', 'files'])
            ->filter(request()->all())
            ->orderBy('stallOwnerId', 'desc')
            ->paginate(10);

        // return $query->paginate(10);
    }

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

    public function show(string $id)
    {
        // $user = User::findOrFail($id);
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
    }

    public function update(array $payload, string $id)
    {
        try {
            return DB::transaction(function () use ($id, $payload) {
                // Find existing Stall Owner
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

    public function delete(string $id)
    {
        $file = StallOwnerFiles::findOrFail($id);
        return $file->delete();

        // return response()->json([
        //     //resource/lang/exception.php
        //     // 'message' => trans('exception.sucess.message')
        //     'message' => "successfully deleted"
        // ], Response::HTTP_OK);
    }
}

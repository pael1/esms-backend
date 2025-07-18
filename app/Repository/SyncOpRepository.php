<?php

namespace App\Repository;

use App\Models\SyncOp;
use App\Models\StallOwnerAccount;
use App\Interface\Repository\SyncOpRepositoryInterface;

class SyncOpRepository implements SyncOpRepositoryInterface
{
    public function findMany(object $payload)
    {
        return SyncOp::where('ownerid', $payload->ownerId)
             ->orderBy('id', 'desc')
             ->paginate(10);
    }

    public function findManyById(string $id)
    {
        return SyncOp::where('ownerid', $id)
                    ->where('is_processed', '0') // or '1' depending on your filter
                    ->get();
    }

    public function findArrears(object $payload)
    {
        return StallOwnerAccount::where('ownerId', $payload->ownerId)
                ->whereNull('ORNum')
                ->where('is_sync', '0')
                ->get();
    }

    public function findById(string $id)
    {
        // return Parameter::where('fieldId', $id);
    }

    public function create(array $payload)
    {
        $syncOp = SyncOp::create($payload);

        return $syncOp->fresh();
    }

    public function update(object $payload, string $id)
    {
        // $user = User::findOrFail($id);
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
    }

    public function delete(string $id)
    {
        // $user = User::findOrFail($id);
        // $user->delete();

        // return response()->json([
        //     //resource/lang/exception.php
        //     // 'message' => trans('exception.sucess.message')
        //     'message' => "successfully deleted"
        // ], Response::HTTP_OK);
    }
}

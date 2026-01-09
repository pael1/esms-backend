<?php

namespace App\Repository;

use Carbon\Carbon;
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

    public function findAllUnprocess()
    {
        return SyncOp::where('is_processed', '0') // or '1' depending on your filter
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

    public function updateById(string $id, string $status)
    {
        return SyncOp::where('id', $id)
            ->update(['is_processed' => $status]);
    }

    public function updatePaidManuallyById(object $payload, string $id, string $status)
    {
        $userId = auth()->id();
        return SyncOp::where('id', $id)->update([
            'is_processed' => $status,
            'paid_manually_by' => $userId,
            'paid_manually_at' => Carbon::now(),
            'reason' => $payload->reason,
        ]);
    }

    public function create(object $payload)
    {
        // $syncOp = SyncOp::create($payload);
        // logger([$payload]);
        $syncOp = new SyncOp();
        $syncOp->ornumber = $payload->ornumber;
        $syncOp->ownerId = $payload->ownerid;
        $syncOp->months = $payload->months;
        $syncOp->save();

        return $syncOp->fresh();

        // return $syncOp->fresh();
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
        // ], Response::HTTP_OK);_OK);TP_OK);
    }
}

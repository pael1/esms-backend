<?php

namespace App\Repository;

use App\Interface\Repository\LedgerRepositoryInterface;
use App\Models\StallOwnerAccount;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LedgerRepository implements LedgerRepositoryInterface
{
    public function findManyLedger(object $payload)
    {
        return StallOwnerAccount::where('ownerId', $payload->ownerId)
            ->orderBy('stallOwnerAccountId', 'desc')
            ->paginate(10);
    }

    public function findManyLedgerArrears(object $payload)
    {
        return StallOwnerAccount::where('ownerId', $payload->ownerId)
            ->whereNull('ORNum')
            ->get();
    }

    public function createLedger(object $payload)
    {
        // $user = new User();
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
    }

    public function updateLedger(object $payload, string $id)
    {
        // $ledger = StallOwnerAccount::where('stallOwnerAccountId', $id);
        // $ledger->ORNum = $payload->ORNum;
        // $ledger->ORDate = $payload->ORDate;
        // $ledger->OPRefId = $payload->oprefId;
        // $ledger->save();

        // return $ledger->fresh();
    }

    public function deleteLedger(string $id)
    {
        // $user = User::findOrFail($id);
        // $user->delete();

        // return response()->json([
        //     //resource/lang/exception.php
        //     // 'message' => trans('exception.sucess.message')
        //     'message' => "successfully deleted"
        // ], Response::HTTP_OK);
    }

    // SYNC FUNCTIONS
    public function updateSync(array $payload)
    {
        StallOwnerAccount::whereIn('stallOwnerAccountId', $payload['months'])->update(['is_sync' => 1]);
    }

    public function updateLedgerSync(array $payload)
    {
        StallOwnerAccount::whereIn('stallOwnerAccountId', $payload['months'])
            ->update([
                'ORNum'   => $payload['ornum'],
                'ORDate'  => $payload['ordate'],
                'OPRefId' => $payload['oprefId']
        ]);
    }
    
}

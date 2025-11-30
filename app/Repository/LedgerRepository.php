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
            ->orderBy('stallOwnerAccountId', 'desc')
            ->get();
    }

    public function createLedger(object $payload, array $item)
    {
        $dateParts = explode(' ', $item['label']);
        $isLedgerExists = $this->checkLedgerExists($payload->ownerId, $item['label']);

        $ledger = new StallOwnerAccount();
        if($item['value'] === 'current' && !$isLedgerExists) {
            try {
                $ledger->ownerId = $payload->ownerId;
                $ledger->month = $dateParts[0];
                $ledger->year = $dateParts[1];
                $ledger->amountBasic = $item['amount_basic'];
                $ledger->isadded = '0';
                $ledger->generatedBy = $payload->postBy;
                $ledger->expdate = $payload->duedate;
                $ledger->save();
            } catch (\Exception $e) {
                logger("Failed to create ledger: " . $e->getMessage());
                return $ledger;
            }
        }
        return $ledger;
    }

    public function checkLedgerExists(string $ownerId, string $date)
    {
        $dateParts = explode(' ', $date);
        return StallOwnerAccount::where('ownerId', $ownerId)
                    ->where('month', $dateParts[0])
                    ->where('year', $dateParts[1])
                    ->first();
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
    public function updateSync(object $payload)
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

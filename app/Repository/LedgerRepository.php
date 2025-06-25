<?php

namespace App\Repository;

use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Http\Response;
use App\Models\StallOwnerAccount;
use Illuminate\Support\Facades\Hash;
use App\Interface\Service\LedgerServiceInterface;
use App\Interface\Repository\UserRepositoryInterface;
use App\Interface\Repository\LedgerRepositoryInterface;

class LedgerRepository implements LedgerRepositoryInterface
{

    public function findManyLedger(object $payload)
    {
        return StallOwnerAccount::where('ownerId', $payload->ownerId)->paginate(10);
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
        // $user = User::findOrFail($id);
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
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
}

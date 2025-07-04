<?php

namespace App\Repository;

use App\Interface\Repository\ChildrenRepositoryInterface;
use App\Models\StallOwnerChild;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ChildrenRepository implements ChildrenRepositoryInterface
{
    public function findMany(object $payload)
    {
        return StallOwnerChild::where('ownerId', $payload->ownerId)->paginate(10);
    }

    public function create(object $payload)
    {
        // $user = new User();
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
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

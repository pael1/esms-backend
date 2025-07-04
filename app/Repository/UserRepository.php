<?php

namespace App\Repository;

use App\Interface\Repository\UserRepositoryInterface;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function findMany(object $payload)
    {
        return UserAccount::with([
            'user_detail',
        ])
            ->orderBy('userid', 'asc')
            ->paginate(10);
    }

    public function findById(string $UserId)
    {
        // return UserAccount::with([
        //     'user_detail'
        // ])->findOrFail($id);
        // dd($UserId);
        return UserAccount::with([
            'user_detail',
        ])->where('UserId', $UserId)->first();
    }

    public function findByUsername(string $username)
    {
        return UserAccount::with([
            // return User::with([
            'user_detail',
            // ])->where('username', $username)->first();
        ])->where('userid', $username)->first();
    }

    public function create(object $payload)
    {
        $user = new User();
        $user->username = $payload->username;
        $user->email = $payload->email;
        $user->password = Hash::make($payload->password);
        $user->save();

        return $user->fresh();
    }

    public function update(object $payload, string $id)
    {
        $user = User::findOrFail($id);
        $user->username = $payload->username;
        $user->email = $payload->email;
        $user->password = Hash::make($payload->password);
        $user->save();

        return $user->fresh();
    }

    public function delete(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            //resource/lang/exception.php
            // 'message' => trans('exception.sucess.message')
            'message' => 'successfully deleted',
        ], Response::HTTP_OK);
    }
}

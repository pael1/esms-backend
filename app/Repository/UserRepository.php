<?php

namespace App\Repository;

use App\Models\OfficeCode;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserAccount;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findMany(object $payload)
    {
        return User::with([
            'details',
        ])
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function findById(string $UserId)
    {
        // return UserAccount::with([
        //     'user_detail',
        // ])->where('UserId', $UserId)->first();
        return User::with([
            'details'
        ])->findOrFail($UserId);
    }

    // public function findByUsername(string $username)
    // {
    //     return UserAccount::with([
    //         // return User::with([
    //         'user_detail',
    //         // ])->where('username', $username)->first();
    //     ])->where('userid', $username)->first();
    // }
    public function findByUsername(string $username)
    {
        return User::with([
            'details',
            ])->where('username', $username)->first();
    }

    public function create(array $payload)
    {
        return DB::transaction(function () use ($payload) {

            // Create user
            $user = User::create([
                'username' => $payload['username'],
                'password' => Hash::make($payload['password']),
                'is_supervisor' => $payload['is_supervisor'] ?? false,
                'is_admin' => $payload['is_admin'] ?? false,
            ]);

            // Create user details
            UserDetail::create([
                'user_id' => $user->id,
                'employee_id' => $payload['employee_id'],
                'firstname' => $payload['firstname'],
                'midinit' => $payload['midinit'] ?? null,
                'lastname' => $payload['lastname'],
                'office' => $payload['office'],
                'position' => $payload['position'],
            ]);

            // Return user with relationship loaded
            return $user->load('details');
        });
    }

    public function update(array $payload, string $id)
    {
        return DB::transaction(function () use ($payload, $id) {
            // Find the main user
            $user = User::findOrFail($id);

            // Update user table
            $user->update([
                'username'      => $payload['username'] ?? $user->username,
                'password'      => isset($payload['password'])
                                    ? Hash::make($payload['password'])
                                    : $user->password,
                'is_supervisor' => $payload['is_supervisor'] ?? $user->is_supervisor,
                'is_admin'      => $payload['is_admin'] ?? $user->is_admin,
            ]);

            // Update user_details table
            $user->details()->update([
                'user_id' => $user->id,
                'employee_id' => $payload['employee_id'] ?? null,
                'firstname'   => $payload['firstname'] ?? null,
                'midinit'     => $payload['midinit'] ?? null,
                'lastname'    => $payload['lastname'] ?? null,
                'office'      => $payload['office'] ?? null,
                'position'    => $payload['position'] ?? null,
            ]);

            // Return fresh model with relation loaded
            return $user->load(['details']);
        });
    }

    public function findOffices()
    {
        return OfficeCode::all();
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

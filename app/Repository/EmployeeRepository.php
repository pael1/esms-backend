<?php

namespace App\Repository;

use App\Models\User;
use App\Models\StallOwnerEmp;
use Illuminate\Http\Response;
use App\Models\StallOwnerChild;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function findMany(object $payload)
    {
        return StallOwnerEmp::where('ownerId', $payload->ownerId)->paginate(10);
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
        $employee = StallOwnerEmp::findOrFail($id);
        return $employee->delete();

        // return response()->json([
        //     //resource/lang/exception.php
        //     // 'message' => trans('exception.sucess.message')
        //     'message' => "successfully deleted"
        // ], Response::HTTP_OK);
    }
}

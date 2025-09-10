<?php

namespace App\Repository;

use App\Models\User;
use App\Models\StallOwnerEmp;
use Illuminate\Http\Response;
use App\Models\StallOwnerChild;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\FileRepositoryInterface;
use App\Models\StallOwnerFiles;

class FileRepository implements FileRepositoryInterface
{
    public function findMany(object $payload)
    {
        return StallOwnerFiles::where('ownerId', $payload->ownerId)->paginate(10);
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
        $file = StallOwnerFiles::findOrFail($id);
        return $file->delete();

        // return response()->json([
        //     //resource/lang/exception.php
        //     // 'message' => trans('exception.sucess.message')
        //     'message' => "successfully deleted"
        // ], Response::HTTP_OK);
    }
}

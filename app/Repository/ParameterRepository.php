<?php

namespace App\Repository;

use App\Interface\Repository\ParameterRepositoryInterface;
use App\Models\Parameter;

class ParameterRepository implements ParameterRepositoryInterface
{
    public function findMany(object $payload)
    {
        return Parameter::where('fieldId', $payload->fieldId)->get();
    }

    public function findByFieldId(string $id)
    {
        // return Parameter::where('fieldId', $id);
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

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

    public function findSubSection(object $payload)
    {
        return Parameter::where('fieldId', $payload->fieldId)
        ->whereRaw('SUBSTRING(fieldValue, 1, 2) = ?', [$payload->section_id])
        ->get();
    }

    public function findByFieldIdFieldValue(string $fieldId, string $fieldValue)
    {
        return Parameter::where([ ['fieldId', $fieldId], ['fieldValue', $fieldValue]])->first();
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

    public function getSection(string $section_code)
    {
        $sectionCode = substr($section_code, 2, 2);
        $sectionName = Parameter::where(['fieldId' => 'SECTIONCODE', 'fieldValue' => $sectionCode])->first();
        $map = [
            'COLD STORAGE' => 'ICE STORAGE',
            // 'VEGETABLE AND FRUIT' => 'FRUIT&VEG',
            'VEGETABLE AND FRUIT' => 'FRUIT',
            'VARIETY OR GROCERIES' => 'VARIETY',
            'RICE, CORN AND OTHER CEREALS' => 'RICE & CORN',
            'FOOD COURT/EATERY' => 'EATERY',
            'LIVESTOCK' => 'LIVE CHICKEN',
        ];

        $sectionCodeDescription = $map[$sectionName->fieldDescription] ?? $sectionName->fieldDescription;

        return $sectionCodeDescription;
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

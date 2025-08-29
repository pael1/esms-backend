<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStallOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lastname'        => 'required|string|max:100',
            'firstname'       => 'required|string|max:100',
            'midinit'         => 'nullable|string|max:5',
            'civilStatus'     => 'required|string|in:SINGLE,MARRIED,WIDOWED,SEPARATED',
            'address'         => 'required|string|max:255',
            'spouseLastname'  => 'nullable|string|max:100',
            'spouseFirstname' => 'nullable|string|max:100',
            'spouseMidint'    => 'nullable|string|max:5',
            'attachIdPhoto'   => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'contactnumber'   => 'required|string|max:20',
            
            // system-generated but still validated if passed
            // 'ownerStatus'     => 'nullable|string|in:ACTIVE,INACTIVE',
            // 'ownerId'         => 'nullable|string',
            // 'dateRegister'    => 'nullable|date',
            'children'             => 'nullable|array',
            'employees'             => 'nullable|array',
            'files'             => 'nullable|array',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRentalRequest extends FormRequest
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
            'busDateStart'      => 'nullable|date',
            'busID'             => 'nullable|string|max:50',
            'busPlateNo'        => 'nullable|string|max:50',
            'capital'           => 'nullable|numeric|min:0',
            'contractEndDate'   => 'nullable|date|after_or_equal:contractStartDate',
            'contractStartDate' => 'nullable|date',
            'lineOfBusiness'    => 'nullable|string|max:255',
            'ownerId'           => 'required|string',
            'stallNo'           => 'required|string',
        ];
    }
}

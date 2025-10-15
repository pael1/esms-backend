<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStallRequest extends FormRequest
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
            'area'    => 'required|numeric|min:1',
            'building'    => 'required|string',
            'class'        => 'required|string',
            //stall id extension
            'extension'      => 'required|string',
            'market'        => 'required|string',
            'section'        => 'required|string',
            'stall_id'        => 'required|string',
            'sub_section'        => 'nullable|string',
            'type'        => 'required|string',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            // Users table fields
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'is_supervisor' => 'nullable|boolean',
            'is_admin' => 'nullable|boolean',

            // User details table fields
            'employee_id' => 'required|string|max:255|unique:user_details,employee_id',
            'firstname' => 'required|string|max:255',
            'midinit' => 'nullable|string|max:10',
            'lastname' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ];
    }
}

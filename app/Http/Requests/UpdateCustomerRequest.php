<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'code' => 'required|string|max:8|unique:customers,code,' . $this->customer->id,
            'phone' => 'nullable|string|max:9|unique:customers,phone,' . $this->customer->id,
            'email' => 'nullable|email|max:150|unique:customers,email,' . $this->customer->id,
            'address' => 'nullable|string|max:150',
            'status' => 'sometimes|boolean',
        ];
    }
}

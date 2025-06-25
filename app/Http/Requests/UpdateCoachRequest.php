<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoachRequest extends FormRequest
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
            'name' => 'required|string|max:80',
            'dni' => 'required|string|max:8|unique:coaches,dni, ' . $this->coach->id,
            'specialty' => 'required|string|max:50, ' . $this->coach->id,
            'phone' => 'nullable|string|max:9|unique:coaches,phone, ' . $this->coach->id,
            'email' => 'nullable|email|max:150|unique:coaches,email, ' . $this->coach->id,
            'address' => 'nullable|string|max:150',
            'status' => 'boolean',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonthlyPaymentRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'type_membership_id' => 'required|exists:type_memberships,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'coach_id' => 'required|exists:coaches,id',
            'months_total' => 'required|integer|min:1|max:36',
            'price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'date_registration' => 'required|date',
            'status' => 'boolean',
        ];
    }
}

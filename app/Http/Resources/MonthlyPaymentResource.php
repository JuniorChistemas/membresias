<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // Relacionales
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer?->full_name,

            'type_membership_id' => $this->type_membership_id,
            'type_membership_name' => $this->typeMembership?->name,
            'type_membership_price' => $this->price,

            'months_total' => $this->months_total,
            'total' => $this->total,

            'payment_method_id' => $this->payment_method_id,
            'payment_method_name' => $this->paymentMethod?->name,

            'coach_id' => $this->coach_id,
            'coach_name' => $this->coach?->name,

            'date_registration' => $this->date_registration,
            'status' => $this->status,

            'created_at' => $this->created_at,
        ];
    }
}

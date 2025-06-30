<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailySessionLogResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'payment_method' => $this->paymentMethod->id,
            'payment_method_name' => $this->paymentMethod->name,
            'registered_at' => $this->registered_at->format('Y-m-d H:i:s'),
        ];
    }
}

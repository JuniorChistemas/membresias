<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type_membership_id',
        'payment_method_id',
        'coach_id',
        'months_total',
        'price',
        'total',
        'date_registration',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'date_registration' => 'date',
    ];

    // Relaciones
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function typeMembership(): BelongsTo
    {
        return $this->belongsTo(TypeMembership::class, 'type_membership_id', 'id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class, 'coach_id', 'id');
    }
}

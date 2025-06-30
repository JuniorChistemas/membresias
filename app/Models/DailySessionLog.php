<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySessionLog extends Model
{
    /** @use HasFactory<\Database\Factories\DailySessionLogFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'payment_method_id',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function paymentMethod():BelongsTo{
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}

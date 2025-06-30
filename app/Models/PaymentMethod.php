<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentMethodFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function dailySessionLog():HasMany{
        return $this->hasMany(DailySessionLog::class);
    }

    public function monthlyPayments():HasMany
    {
        return $this->hasMany(MonthlyPayment::class, 'payment_method_id');
    }
}

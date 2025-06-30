<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coach extends Model
{
    /** @use HasFactory<\Database\Factories\CoachFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'dni',
        'specialty',
        'phone',
        'email',
        'address',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function monthlyPayments(): HasMany
    {
        return $this->hasMany(MonthlyPayment::class);
    }
}

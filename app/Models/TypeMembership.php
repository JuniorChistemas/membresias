<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeMembership extends Model
{
    /** @use HasFactory<\Database\Factories\TypeMembershipFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function monthlyPayments(): HasMany
    {
        return $this->hasMany(MonthlyPayment::class, 'type_membership_id');
    }
}

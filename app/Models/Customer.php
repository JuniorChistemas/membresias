<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'code',
        'phone',
        'email',
        'address',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function getCodeAttribute($value): string
    {
        return strtoupper($value);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'address', 'balance'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}

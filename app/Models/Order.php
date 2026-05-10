<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'customer_id', 'total_amount', 'discount_amount',
        'discount_type', 'payment_method', 'status',
        'type', 'refund_of_order_id', 'refund_reason',
    ];

    public function customer()   { return $this->belongsTo(Customer::class); }
    public function user()       { return $this->belongsTo(User::class); }
    public function items()      { return $this->hasMany(OrderItem::class); }

    /** The refund orders created against this sale */
    public function refunds()    { return $this->hasMany(Order::class, 'refund_of_order_id'); }

    /** The original sale this refund is reversing */
    public function originalOrder() { return $this->belongsTo(Order::class, 'refund_of_order_id'); }

    public function isRefund()   { return $this->type === 'refund'; }
    public function isSale()     { return $this->type === 'sale'; }
}

<?php

namespace App\Models;

use App\Models\Order_items;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'address_id',
        'status',
        'payment_status',
        'subtotal',
        'delivery_fee',
        'total_price',
        'special_instructions',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(Order_items::class, 'order_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}

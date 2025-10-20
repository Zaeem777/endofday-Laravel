<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'address_id',
        'listing_ids',
        'status',
        'payment_status',
        'subtotal',
        'delivery_fee',
        'total_price',
        'special_instructions',
    ];
    protected $casts = [
        'listing_ids' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }

    // public function listing()
    // {
    //     return $this->hasMany(Listings::class, 'id', 'listing_ids');
    // }

    public function order_items()
    {
        return $this->hasMany(Order_items::class, 'id', 'order_id');
    }
}

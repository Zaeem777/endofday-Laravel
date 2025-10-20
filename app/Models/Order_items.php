<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_items extends Model
{
    protected $table = 'orderitems';

    protected $fillable = [
        'listing_id',
        'order_id',
        'quantity',
        'price',
    ];

    public function items()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function listing()
    {
        return $this->has(Listings::class, 'listing_id');
    }
}

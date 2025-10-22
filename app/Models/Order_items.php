<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Listings;

class Order_items extends Model
{
    protected $table = 'orderitems';

    protected $fillable = [
        'listing_id',
        'order_id',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function listing()
    {
        return $this->belongsTo(
            Listings::class,
            'listing_id'
        );
    }
}

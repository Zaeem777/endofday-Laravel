<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listings extends Model
{
    protected $fillable = [
        'name',
        'price',
        'discountedprice',
        'category',
        'remainingitem',
        'image',
        'manufacturedate',
        'description',
        'bakery_id'
    ];

     public function owner()
{
    return $this->belongsTo(User::class, 'bakery_id')
                ->where('role', 'restaurant_owner');
}

public function carts()
{
    return $this->hasMany(Cart::class, 'listing_id');
}

}

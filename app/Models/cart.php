<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    //
 protected $fillable = [
        'listing_id',
        'customer_id',
        'quantity',
        'price',
    ];
    
     public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * A cart belongs to a listing (product/item).
     */
    public function listing()
    {
        return $this->belongsTo(Listings::class, 'listing_id');
    } 
}

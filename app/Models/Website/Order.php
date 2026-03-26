<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'email',
        'phone',

        'receiver_name',
        'receiver_phone',
        'address',
        'city',
        'state',
        'pincode',

        'total_amount',
        'payment_method',
        'payment_status',
        'order_status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function tracks()
    {
        return $this->hasMany(OrderTrack::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_gateway',
        'transaction_id',
        'payment_id',
        'amount',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

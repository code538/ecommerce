<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'message'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

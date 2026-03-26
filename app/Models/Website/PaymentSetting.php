<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'mode',
        'test_key',
        'test_secret',
        'live_key',
        'live_secret',
        'status'
    ];
    
}

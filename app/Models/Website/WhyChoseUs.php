<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;

class WhyChoseUs extends Model
{
    protected $table = 'why_chose_us';

    protected $fillable = [
        'badge',
        'title',
        'description',
        'title_meta',
        'description_meta',
        'status',
        'short_order'
    ];

    protected $casts = [
        'status' => 'boolean',
        'short_order' => 'integer'
    ];
}
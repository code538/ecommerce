<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'rating',
        'review',
        'review_type',
        'video',
        'image',
        'youtube_shorts',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
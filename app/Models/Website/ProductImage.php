<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
        'image_alt',
        'is_thumbnail',
        'sort_order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
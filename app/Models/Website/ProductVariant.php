<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_name',
        'color',
        'size',
        'price',
        'sale_price',
        'stock'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

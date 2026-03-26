<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

     protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'name_meta',
        'tag_line',
        'premium_product',
        'price',
        'sale_price',
        'stock',
        'rating',
        'review_count',
        'description',
        'description_meta',
        'shipping_policy',
        'shipping_policy_meta',
        'return_policy',
        'return_policy_meta',
        'status'
    ];

    // Product belongs to category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Product belongs to subcategory
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}

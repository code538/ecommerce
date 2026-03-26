<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'feature',
        'feature_meta'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
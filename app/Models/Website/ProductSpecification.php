<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'spec_key',
        'spec_key_meta',
        'spec_value',
        'spec_value_meta'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
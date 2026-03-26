<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'image_alt',
        'name_meta',
        'status'
    ];

    // Category has many subcategories
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    // Category has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

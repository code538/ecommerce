<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'slug',
        'page_heading',
        'short_desc',
        'description',
        'image',
        'image_alt',
        'image_align',
        'status'
    ];

    // ✅ Auto-generate slug if not provided
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->page_name) . '-' . time();
            }
        });
    }

    // public function getImageAttribute($value)
    // {
    //     return $value ? asset('storage/' . $value) : null;
    // }
}
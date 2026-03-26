<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'title_slug',
        'title_meta',
        'short_desc',
        'short_desc_meta',
        'long_desc',
        'long_desc_meta',
        'web_image',
        'mobile_image',
        'image_alt',
        'youtube_link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

      protected $appends = [
        'web_image_url',
        'mobile_image_url',
    ];

  
    public function getWebImageUrlAttribute()
    {
        if (!$this->web_image) {
            return null;
        }

        return asset('storage/app/public/' . $this->web_image);
    }

   
    public function getMobileImageUrlAttribute()
    {
        if (!$this->mobile_image) {
            return null;
        }

        return asset('storage/app/public/' . $this->mobile_image);
    }
}

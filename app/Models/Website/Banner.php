<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_text',
        'title',
        'description',
        
        'image',
        'image_alt',
        'video',
        'youtube_url',
        'banner_type',

        'button1_text',
        'button1_link',
        'button2_text',
        'button2_link',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Return full image URL
     */
    // public function getImageAttribute($value)
    // {
    //     return $value ? asset('storage/'.$value) : null;
    // }
}

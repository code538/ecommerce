<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'page_desc',
        'page_image',
        'heading',
        'badge_text',
        'heading_meta',
        'description',
        'desc_meta',
        'image',
        'image_alt'
    ];
}
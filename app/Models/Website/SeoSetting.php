<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $table = 'seo_settings';

    protected $fillable = [
        'page_key',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'robots',
        'schema_json',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'schema_json' => 'array',
    ];
}

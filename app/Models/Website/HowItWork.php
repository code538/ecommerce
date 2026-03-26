<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowItWork extends Model
{
    protected $fillable = [
        'section_title',
        'section_subtitle',
        'tab_name',
        'youtube_url',
        'video_title',
        'step1',
        'step2',
        'step3',
        'step4',
        'feature1',
        'feature2',
        'feature3',
        'feature4',
        'button_text',
        'button_link',
        'status',
        'sort_order'
    ];
}
